<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestockRequest;
use App\Models\RestockRequestItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RestockRequestController extends Controller
{
    /**
     * Display a listing of restock requests.
     */
    public function index(Request $request)
    {
        $query = RestockRequest::with(['supplier', 'createdBy', 'items']);

        if ($request->filled('search')) {
            $query->where('request_number', 'like', "%{$request->search}%")
                ->orWhereHas('supplier', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('request_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('request_date', '<=', $request->date_to);
        }

        $requests  = $query->latest()->paginate(20);
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total'         => RestockRequest::count(),
            'draft'         => RestockRequest::where('status', 'draft')->count(),
            'sent'          => RestockRequest::where('status', 'sent')->count(),
            'acknowledged'  => RestockRequest::where('status', 'acknowledged')->count(),
            'ordered'       => RestockRequest::where('status', 'ordered')->count(),
            'received'      => RestockRequest::where('status', 'received')->count(),
            'closed'        => RestockRequest::where('status', 'closed')->count(),
            'cancelled'     => RestockRequest::where('status', 'cancelled')->count(),
        ];

        return view('admin.restock.index', compact('requests', 'suppliers', 'stats'));
    }

    /**
     * Show the form for creating a new restock request.
     */
    public function create(Request $request)
    {
        $productId       = $request->product_id;
        $selectedProduct = null;
        $defaultSupplier = null;

        if ($productId) {
            // NOTE: eager load 'defaultSupplierRelation' (a real relation),
            // NOT 'defaultSupplier' (which executes a query and returns a model/null).
            $selectedProduct = Product::with(['inventory', 'defaultSupplierRelation'])
                ->find($productId);

            if ($selectedProduct) {
                $defaultSupplier = $selectedProduct->defaultSupplierRelation->first();
            }
        }

        $products  = Product::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('admin.restock.create', compact('products', 'suppliers', 'selectedProduct', 'defaultSupplier'));
    }

    /**
     * Store a newly created restock request.
     */
/**
 * Store a newly created restock request.
 */
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'supplier_id'                    => 'required|exists:suppliers,id',
        'request_date'                    => 'required|date',
        'expected_delivery_date'          => 'nullable|date|after_or_equal:request_date',
        'notes'                            => 'nullable|string',
        'items'                            => 'required|array|min:1',
        'items.*.product_id'              => 'required|exists:products,id',
        'items.*.quantity_requested'      => 'required|integer|min:1',
        'items.*.unit_cost'               => 'nullable|numeric|min:0',
        'items.*.supplier_sku'            => 'nullable|string|max:255',
        'items.*.notes'                    => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        DB::beginTransaction();

        $requestNumber = (new RestockRequest())->generateRequestNumber();

        // Get the admin ID - use auth()->id() if you're using the default guard
        $adminId = auth()->guard('admin')->id() ?? auth()->id();

        $restockRequest = RestockRequest::create([
            'request_number'         => $requestNumber,
            'supplier_id'            => $request->supplier_id,
            'created_by'             => $adminId,
            'request_date'           => $request->request_date,
            'expected_delivery_date' => $request->expected_delivery_date,
            'status'                 => 'draft',
            'notes'                  => $request->notes,
        ]);

        foreach ($request->items as $item) {
            $restockRequest->items()->create([
                'product_id'          => $item['product_id'],
                'quantity_requested'  => $item['quantity_requested'],
                'quantity_received'   => 0,
                'unit_cost'           => $item['unit_cost'] ?? null,
                'total_cost'          => isset($item['unit_cost'])
                                            ? $item['unit_cost'] * $item['quantity_requested']
                                            : null,
                'supplier_sku'        => $item['supplier_sku'] ?? null,
                'notes'               => $item['notes'] ?? null,
            ]);
        }

        $restockRequest->updateStatus('draft', $adminId, 'Request created');

        DB::commit();

        return redirect()->route('admin.restock.show', $restockRequest->id)
            ->with('success', "Restock request {$requestNumber} created successfully!");

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Failed to create restock request: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Display the specified restock request.
     */
    public function show($id)
    {
        $restockRequest = RestockRequest::with([
            'supplier',
            'createdBy',
            'items.product',
            'items.product.inventory',
            'statusHistory.performedBy',
            'purchaseOrder',
        ])->findOrFail($id);

        return view('admin.restock.show', compact('restockRequest'));
    }

    /**
     * Show the form for editing the specified restock request.
     */
    public function edit($id)
    {
        $restockRequest = RestockRequest::with(['items'])->findOrFail($id);

        if (!$restockRequest->canBeEdited()) {
            return redirect()->route('admin.restock.show', $restockRequest->id)
                ->with('error', 'This request cannot be edited.');
        }

        $products  = Product::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('admin.restock.edit', compact('restockRequest', 'products', 'suppliers'));
    }

    /**
     * Update the specified restock request.
     */
    public function update(Request $request, $id)
    {
        $restockRequest = RestockRequest::findOrFail($id);

        if (!$restockRequest->canBeEdited()) {
            return redirect()->route('admin.restock.show', $restockRequest->id)
                ->with('error', 'This request cannot be edited.');
        }

        $validator = Validator::make($request->all(), [
            'supplier_id'                    => 'required|exists:suppliers,id',
            'request_date'                    => 'required|date',
            'expected_delivery_date'          => 'nullable|date|after_or_equal:request_date',
            'notes'                            => 'nullable|string',
            'items'                            => 'required|array|min:1',
            'items.*.id'                       => 'nullable|exists:restock_request_items,id',
            'items.*.product_id'              => 'required|exists:products,id',
            'items.*.quantity_requested'      => 'required|integer|min:1',
            'items.*.unit_cost'               => 'nullable|numeric|min:0',
            'items.*.supplier_sku'            => 'nullable|string|max:255',
            'items.*.notes'                    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $restockRequest->update([
                'supplier_id'            => $request->supplier_id,
                'request_date'           => $request->request_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'notes'                  => $request->notes,
            ]);

            $currentItemIds = $restockRequest->items->pluck('id')->toArray();
            $updatedItemIds = [];

            foreach ($request->items as $item) {
                if (isset($item['id']) && in_array($item['id'], $currentItemIds)) {
                    $requestItem = RestockRequestItem::find($item['id']);
                    $requestItem->update([
                        'product_id'         => $item['product_id'],
                        'quantity_requested' => $item['quantity_requested'],
                        'unit_cost'          => $item['unit_cost'] ?? null,
                        'total_cost'         => isset($item['unit_cost'])
                                                    ? $item['unit_cost'] * $item['quantity_requested']
                                                    : null,
                        'supplier_sku'       => $item['supplier_sku'] ?? null,
                        'notes'              => $item['notes'] ?? null,
                    ]);
                    $updatedItemIds[] = $item['id'];
                } else {
                    $newItem = $restockRequest->items()->create([
                        'product_id'          => $item['product_id'],
                        'quantity_requested'  => $item['quantity_requested'],
                        'quantity_received'   => 0,
                        'unit_cost'           => $item['unit_cost'] ?? null,
                        'total_cost'          => isset($item['unit_cost'])
                                                    ? $item['unit_cost'] * $item['quantity_requested']
                                                    : null,
                        'supplier_sku'        => $item['supplier_sku'] ?? null,
                        'notes'               => $item['notes'] ?? null,
                    ]);
                    $updatedItemIds[] = $newItem->id;
                }
            }

            $itemsToDelete = array_diff($currentItemIds, $updatedItemIds);
            if (!empty($itemsToDelete)) {
                RestockRequestItem::whereIn('id', $itemsToDelete)->delete();
            }

            DB::commit();

            return redirect()->route('admin.restock.show', $restockRequest->id)
                ->with('success', 'Restock request updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update restock request: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified restock request.
     */
    public function destroy($id)
    {
        $restockRequest = RestockRequest::findOrFail($id);

        if ($restockRequest->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Only draft requests can be deleted.');
        }

        $restockRequest->delete();

        return redirect()->route('admin.restock.index')
            ->with('success', 'Restock request deleted successfully!');
    }

    /**
     * Send request to supplier.
     */
    public function send($id)
    {
        $restockRequest = RestockRequest::findOrFail($id);

        if ($restockRequest->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Only draft requests can be sent.');
        }

        $restockRequest->updateStatus('sent', Auth::guard('admin')->id(), 'Request sent to supplier');

        return redirect()->route('admin.restock.show', $restockRequest->id)
            ->with('success', 'Request sent to supplier successfully!');
    }

    /**
     * Acknowledge request (supplier confirmed).
     */
    public function acknowledge($id)
    {
        $restockRequest = RestockRequest::findOrFail($id);

        if ($restockRequest->status !== 'sent') {
            return redirect()->back()
                ->with('error', 'Only sent requests can be acknowledged.');
        }

        $restockRequest->updateStatus('acknowledged', Auth::guard('admin')->id(), 'Supplier acknowledged the request');

        return redirect()->route('admin.restock.show', $restockRequest->id)
            ->with('success', 'Request acknowledged successfully!');
    }

    /**
     * Cancel the request.
     */
    public function cancel($id)
    {
        $restockRequest = RestockRequest::findOrFail($id);

        if (!$restockRequest->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'This request cannot be cancelled.');
        }

        $restockRequest->updateStatus('cancelled', Auth::guard('admin')->id(), 'Request cancelled');

        return redirect()->route('admin.restock.show', $restockRequest->id)
            ->with('success', 'Request cancelled successfully!');
    }

    /**
     * Close the request.
     */
    public function close($id)
    {
        $restockRequest = RestockRequest::findOrFail($id);

        if ($restockRequest->status !== 'received') {
            return redirect()->back()
                ->with('error', 'Only received requests can be closed.');
        }

        $restockRequest->updateStatus('closed', Auth::guard('admin')->id(), 'Request closed');

        return redirect()->route('admin.restock.show', $restockRequest->id)
            ->with('success', 'Request closed successfully!');
    }

    /**
     * Receive stock for a request item.
     */
    public function receiveStock(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $restockRequest = RestockRequest::findOrFail($id);

        if (!$restockRequest->canReceiveStock()) {
            return redirect()->back()
                ->with('error', 'Stock cannot be received for this request.');
        }

        try {
            DB::beginTransaction();

            $restockRequest->receiveStock(
                $request->product_id,
                $request->quantity,
                Auth::guard('admin')->id()
            );

            DB::commit();

            return redirect()->route('admin.restock.show', $restockRequest->id)
                ->with('success', 'Stock received successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to receive stock: ' . $e->getMessage());
        }
    }

    /**
     * Search products for AJAX product picker (used in create/edit forms).
     */
    public function searchProducts(Request $request)
    {
        $search = trim($request->get('search', ''));

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $products = Product::with('inventory')
            ->where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id'    => $product->id,
                    'name'  => $product->name,
                    'sku'   => $product->sku,
                    'stock' => $product->inventory?->quantity_on_hand ?? 0,
                ];
            });

        return response()->json($products);
    }

    /**
     * Get product details for AJAX (for dynamic form).
     */
    public function getProductDetails($id)
    {
        // 'suppliers' IS a real relation (belongsToMany without ->first()),
        // so eager loading it here is safe.
        $product = Product::with(['inventory', 'suppliers' => function ($q) {
            $q->wherePivot('is_default', true);
        }])->findOrFail($id);

        $currentPrice    = $product->current_price;
        $stock           = $product->inventory?->quantity_on_hand ?? 0;
        $reorderLevel    = $product->inventory?->reorder_level ?? 5;
        $defaultSupplier = $product->suppliers->first();

        return response()->json([
            'id'                    => $product->id,
            'name'                  => $product->name,
            'sku'                   => $product->sku,
            'current_price'         => $currentPrice,
            'stock'                 => $stock,
            'reorder_level'         => $reorderLevel,
            'default_supplier_id'   => $defaultSupplier?->id,
            'default_supplier_name' => $defaultSupplier?->name,
            'supplier_sku'          => $defaultSupplier?->pivot->supplier_sku,
            'supplier_price'        => $defaultSupplier?->pivot->supplier_price,
        ]);
    }
}