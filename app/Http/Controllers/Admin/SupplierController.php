<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $suppliers = $query->latest()->paginate(20);

        // Get statistics
        $totalSuppliers = Supplier::count();
        $activeSuppliers = Supplier::where('is_active', true)->count();
        $inactiveSuppliers = Supplier::where('is_active', false)->count();

        return view('admin.suppliers.index', compact(
            'suppliers',
            'totalSuppliers',
            'activeSuppliers',
            'inactiveSuppliers'
        ));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'tax_number' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Supplier::create([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'website' => $request->website,
            'tax_number' => $request->tax_number,
            'payment_terms' => $request->payment_terms,
            'notes' => $request->notes,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier created successfully!');
    }

    /**
     * Display the specified supplier.
     */
    public function show($id)
    {
        $supplier = Supplier::with(['products' => function($query) {
            $query->with(['category', 'inventory']);
        }])->findOrFail($id);

        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'tax_number' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'website' => $request->website,
            'tax_number' => $request->tax_number,
            'payment_terms' => $request->payment_terms,
            'notes' => $request->notes,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier updated successfully!');
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Check if supplier has products
        if ($supplier->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete supplier with associated products. Remove products first.');
        }

        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier deleted successfully!');
    }

    /**
     * Toggle supplier status.
     */
    public function toggleStatus($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'is_active' => !$supplier->is_active
        ]);

        $status = $supplier->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Supplier {$status} successfully!");
    }
}