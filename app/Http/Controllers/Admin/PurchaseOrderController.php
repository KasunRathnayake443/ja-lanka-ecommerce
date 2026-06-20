<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\RestockRequest;
use App\Models\RestockRequestItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of purchase orders.
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'createdBy', 'restockRequest']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('po_number', 'like', "%{$request->search}%")
                ->orWhereHas('supplier', function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by supplier
        if ($request->has('supplier') && $request->supplier != '') {
            $query->where('supplier_id', $request->supplier);
        }

        $purchaseOrders = $query->latest()->paginate(20);
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        // Statistics
        $stats = [
            'total' => PurchaseOrder::count(),
            'draft' => PurchaseOrder::where('status', 'draft')->count(),
            'sent' => PurchaseOrder::where('status', 'sent')->count(),
            'received' => PurchaseOrder::where('status', 'received')->count(),
            'cancelled' => PurchaseOrder::where('status', 'cancelled')->count(),
        ];

        return view('admin.purchase-orders.index', compact('purchaseOrders', 'suppliers', 'stats'));
    }

    /**
     * Show the form for creating a new purchase order.
     */
    public function create(Request $request)
    {
        $restockRequestId = $request->restock_request_id;
        $restockRequest = null;
        $selectedProducts = [];

        if ($restockRequestId) {
            $restockRequest = RestockRequest::with(['items.product', 'supplier'])->find($restockRequestId);
            if ($restockRequest) {
                foreach ($restockRequest->items as $item) {
                    $selectedProducts[] = [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'product_sku' => $item->product->sku,
                        'quantity_ordered' => $item->quantity_requested,
                        'unit_cost' => $item->unit_cost ?? $item->product->regular_price,
                        'supplier_sku' => $item->supplier_sku,
                    ];
                }
            }
        }

        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.purchase-orders.create', compact(
            'suppliers', 
            'products', 
            'restockRequest', 
            'selectedProducts'
        ));
    }

    /**
     * Store a newly created purchase order.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'restock_request_id' => 'nullable|exists:restock_requests,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.supplier_sku' => 'nullable|string|max:255',
            'items.*.notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the purchase order
            $poNumber = (new PurchaseOrder())->generatePONumber();
            
            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $request->supplier_id,
                'restock_request_id' => $request->restock_request_id,
                'created_by' => Auth::id(),
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'tax_amount' => $request->tax_amount ?? 0,
                'shipping_amount' => $request->shipping_amount ?? 0,
                'status' => 'draft',
                'notes' => $request->notes,
            ]);

            // Create PO items
            $subtotal = 0;
            foreach ($request->items as $item) {
                $totalCost = $item['quantity_ordered'] * $item['unit_cost'];
                $subtotal += $totalCost;

                $purchaseOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity_ordered'],
                    'quantity_received' => 0,
                    'unit_cost' => $item['unit_cost'],
                    'total_cost' => $totalCost,
                    'supplier_sku' => $item['supplier_sku'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            // Update totals
            $purchaseOrder->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + $purchaseOrder->tax_amount + $purchaseOrder->shipping_amount,
            ]);

            // If linked to restock request, update its status
            if ($request->restock_request_id) {
                $restockRequest = RestockRequest::find($request->restock_request_id);
                if ($restockRequest && $restockRequest->status === 'acknowledged') {
                    $restockRequest->updateStatus('ordered', Auth::id(), 'Purchase order created');
                }
            }

            DB::commit();

            return redirect()->route('admin.purchase-orders.show', $purchaseOrder->id)
                ->with('success', "Purchase order {$poNumber} created successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create purchase order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified purchase order.
     */
    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with([
            'supplier',
            'createdBy',
            'restockRequest',
            'items.product',
            'items.product.inventory'
        ])->findOrFail($id);

        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified purchase order.
     */
    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::with(['items'])->findOrFail($id);
        
        // Only allow editing if in draft status
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('admin.purchase-orders.show', $purchaseOrder->id)
                ->with('error', 'This purchase order cannot be edited.');
        }

        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    /**
     * Update the specified purchase order.
     */
    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('admin.purchase-orders.show', $purchaseOrder->id)
                ->with('error', 'This purchase order cannot be edited.');
        }

        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:purchase_order_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.supplier_sku' => 'nullable|string|max:255',
            'items.*.notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update the purchase order
            $purchaseOrder->update([
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'tax_amount' => $request->tax_amount ?? 0,
                'shipping_amount' => $request->shipping_amount ?? 0,
                'notes' => $request->notes,
            ]);

            // Get current item IDs
            $currentItemIds = $purchaseOrder->items->pluck('id')->toArray();
            $updatedItemIds = [];
            $subtotal = 0;

            // Update or create items
            foreach ($request->items as $item) {
                $totalCost = $item['quantity_ordered'] * $item['unit_cost'];
                $subtotal += $totalCost;

                if (isset($item['id']) && in_array($item['id'], $currentItemIds)) {
                    // Update existing item
                    $poItem = PurchaseOrderItem::find($item['id']);
                    $poItem->update([
                        'product_id' => $item['product_id'],
                        'quantity_ordered' => $item['quantity_ordered'],
                        'unit_cost' => $item['unit_cost'],
                        'total_cost' => $totalCost,
                        'supplier_sku' => $item['supplier_sku'] ?? null,
                        'notes' => $item['notes'] ?? null,
                    ]);
                    $updatedItemIds[] = $item['id'];
                } else {
                    // Create new item
                    $newItem = $purchaseOrder->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity_ordered' => $item['quantity_ordered'],
                        'quantity_received' => 0,
                        'unit_cost' => $item['unit_cost'],
                        'total_cost' => $totalCost,
                        'supplier_sku' => $item['supplier_sku'] ?? null,
                        'notes' => $item['notes'] ?? null,
                    ]);
                    $updatedItemIds[] = $newItem->id;
                }
            }

            // Delete removed items
            $itemsToDelete = array_diff($currentItemIds, $updatedItemIds);
            if (!empty($itemsToDelete)) {
                PurchaseOrderItem::whereIn('id', $itemsToDelete)->delete();
            }

            // Update totals
            $purchaseOrder->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + $purchaseOrder->tax_amount + $purchaseOrder->shipping_amount,
            ]);

            DB::commit();

            return redirect()->route('admin.purchase-orders.show', $purchaseOrder->id)
                ->with('success', 'Purchase order updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update purchase order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified purchase order.
     */
    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        
        // Only allow deletion if in draft status
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Only draft purchase orders can be deleted.');
        }

        $purchaseOrder->delete();

        return redirect()->route('admin.purchase-orders.index')
            ->with('success', 'Purchase order deleted successfully!');
    }

    /**
     * Send purchase order to supplier.
     */
    public function send($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Only draft purchase orders can be sent.');
        }

        $purchaseOrder->update(['status' => 'sent']);

        // Update linked restock request if exists
        if ($purchaseOrder->restock_request_id) {
            $restockRequest = RestockRequest::find($purchaseOrder->restock_request_id);
            if ($restockRequest && $restockRequest->status === 'ordered') {
                // Keep as ordered, no change
            }
        }

        return redirect()->route('admin.purchase-orders.show', $purchaseOrder->id)
            ->with('success', 'Purchase order sent to supplier successfully!');
    }

    /**
     * Mark purchase order as received and update inventory.
     */
    public function receive($id)
    {
        $purchaseOrder = PurchaseOrder::with(['items'])->findOrFail($id);
        
        if ($purchaseOrder->status !== 'sent') {
            return redirect()->back()
                ->with('error', 'Only sent purchase orders can be marked as received.');
        }

        try {
            DB::beginTransaction();

            // Update PO status
            $purchaseOrder->update([
                'status' => 'received',
                'actual_delivery_date' => now()->toDateString(),
            ]);

            // ✅ UPDATE INVENTORY FOR EACH PRODUCT
            foreach ($purchaseOrder->items as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                
                if ($inventory) {
                    // Add the received quantity to quantity_on_hand
                    $inventory->increment('quantity_on_hand', $item->quantity_ordered);
                    
                    // Update the item's quantity_received
                    $item->update([
                        'quantity_received' => $item->quantity_ordered
                    ]);
                } else {
                    // If no inventory record exists, create one
                    Inventory::create([
                        'product_id' => $item->product_id,
                        'quantity_on_hand' => $item->quantity_ordered,
                        'quantity_sold' => 0,
                        'quantity_reserved' => 0,
                        'reorder_level' => 5,
                    ]);
                }
            }

            // Update linked restock request if exists
            if ($purchaseOrder->restock_request_id) {
                $restockRequest = RestockRequest::find($purchaseOrder->restock_request_id);
                if ($restockRequest && $restockRequest->status === 'ordered') {
                    // Update each restock item's quantity_received
                    foreach ($purchaseOrder->items as $item) {
                        $restockItem = RestockRequestItem::where('restock_request_id', $restockRequest->id)
                            ->where('product_id', $item->product_id)
                            ->first();
                        
                        if ($restockItem) {
                            $restockItem->update([
                                'quantity_received' => $restockItem->quantity_received + $item->quantity_ordered
                            ]);
                        }
                    }
                    
                    // Update restock request status
                    $restockRequest->updateStatus('received', Auth::id(), 'Purchase order received - stock updated');
                }
            }

            DB::commit();

            return redirect()->route('admin.purchase-orders.show', $purchaseOrder->id)
                ->with('success', 'Purchase order marked as received! Stock has been updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update stock: ' . $e->getMessage());
        }
    }

    /**
     * Cancel purchase order.
     */
    public function cancel($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        
        if (in_array($purchaseOrder->status, ['received', 'cancelled'])) {
            return redirect()->back()
                ->with('error', 'This purchase order cannot be cancelled.');
        }

        $purchaseOrder->update(['status' => 'cancelled']);

        // Update linked restock request if exists
        if ($purchaseOrder->restock_request_id) {
            $restockRequest = RestockRequest::find($purchaseOrder->restock_request_id);
            if ($restockRequest && $restockRequest->status === 'ordered') {
                $restockRequest->updateStatus('cancelled', Auth::id(), 'Purchase order cancelled');
            }
        }

        return redirect()->route('admin.purchase-orders.show', $purchaseOrder->id)
            ->with('success', 'Purchase order cancelled successfully!');
    }
}