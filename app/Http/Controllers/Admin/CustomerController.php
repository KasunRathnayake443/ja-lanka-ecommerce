<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $customers = $query->paginate(15);

        // Get statistics
        $totalCustomers = User::count();
        $newThisMonth = User::whereMonth('created_at', now()->month)->count();
        $activeCustomers = User::where('is_active', true)->count();
        $inactiveCustomers = User::where('is_active', false)->count();

        return view('admin.customers.index', compact('customers', 'totalCustomers', 'newThisMonth', 'activeCustomers', 'inactiveCustomers'));
    }

    public function show($id)
    {
        $customer = User::with(['addresses', 'wishlist'])->findOrFail($id);
        
        // Get orders with items count
        $orders = Order::where('user_id', $customer->id)
            ->with('items')
            ->latest()
            ->get();
        
        $totalSpent = Order::where('user_id', $customer->id)
            ->where('payment_status', 'paid')
            ->sum('grand_total');
        
        $totalOrders = $orders->count();
        $wishlistCount = $customer->wishlist->count();
        $addressCount = $customer->addresses->count();
        
        return view('admin.customers.show', compact('customer', 'orders', 'totalSpent', 'totalOrders', 'wishlistCount', 'addressCount'));
    }

    public function edit($id)
    {
        $customer = User::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'mobile' => 'nullable|string|max:20',
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ]);

        if ($request->hasFile('profile_photo')) {
            $request->validate(['profile_photo' => 'image|mimes:jpeg,png,jpg|max:2048']);
            if ($customer->profile_photo) {
                \Storage::disk('public')->delete($customer->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $customer->update(['profile_photo' => $path]);
        }

        return redirect()->route('admin.customers.show', $customer->id)
            ->with('success', 'Customer updated successfully!');
    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        
        // Check if customer has orders
        if (Order::where('user_id', $customer->id)->count() > 0) {
            return back()->with('error', 'Cannot delete customer with orders!');
        }
        
        // Delete profile photo
        if ($customer->profile_photo) {
            \Storage::disk('public')->delete($customer->profile_photo);
        }
        
        $customer->delete();
        
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $customer = User::findOrFail($id);
        $customer->update(['is_active' => !$customer->is_active]);
        
        $status = $customer->is_active ? 'activated' : 'blocked';
        return redirect()->route('admin.customers.show', $customer->id)
            ->with('success', "Customer {$status} successfully!");
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        
        $customer = User::findOrFail($id);
        $customer->update(['password' => Hash::make($request->password)]);
        
        return redirect()->route('admin.customers.show', $customer->id)
            ->with('success', 'Password reset successfully!');
    }

    public function impersonate($id)
    {
        $customer = User::findOrFail($id);
        
        // Store admin ID to return later
        Session::put('admin_impersonating', auth()->id());
        
        // Login as customer
        auth()->login($customer);
        
        return redirect()->route('home')->with('success', "You are now viewing as {$customer->name}");
    }

    public function stopImpersonate()
    {
        $adminId = Session::get('admin_impersonating');
        
        if ($adminId) {
            $admin = User::find($adminId);
            Session::forget('admin_impersonating');
            auth()->login($admin);
            return redirect()->route('admin.customers.index')->with('success', 'Back to admin mode');
        }
        
        return redirect()->route('admin.customers.index');
    }
}