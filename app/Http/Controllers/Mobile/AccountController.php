<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    // Remove the __construct method entirely
    
    public function index()
    {
        if (Auth::check()) {
            $totalOrders = Order::where('user_id', Auth::id())->count();
            $totalSpent = Order::where('user_id', Auth::id())->where('payment_status', 'paid')->sum('grand_total');
            $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            
            return view('mobile.account.index', compact('totalOrders', 'totalSpent', 'wishlistCount'));
        }
        
        return view('mobile.account.index');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)->where('payment_status', 'paid')->sum('grand_total');
        
        return view('mobile.account.dashboard', compact('user', 'recentOrders', 'wishlistCount', 'totalOrders', 'totalSpent'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(10);
        return view('mobile.account.orders', compact('orders'));
    }

    public function orderDetail($id)
    {
        $order = Order::where('user_id', Auth::id())->with('items.product', 'shippingAddress')->findOrFail($id);
        return view('mobile.account.order-detail', compact('order'));
    }

    public function wishlist()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->with('product.images')->get();
        return view('mobile.account.wishlist', compact('wishlist'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('mobile.account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ]);

        if ($request->hasFile('profile_photo')) {
            $request->validate(['profile_photo' => 'image|mimes:jpeg,png,jpg|max:2048']);
            if ($user->profile_photo) Storage::disk('public')->delete($user->profile_photo);
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $user->update(['profile_photo' => $path]);
        }

        return back()->with('success', 'Profile updated!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success', 'Password changed!');
    }

    public function addresses()
    {
        $addresses = Address::where('user_id', Auth::id())->orderBy('is_default', 'desc')->get();
        return view('mobile.account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
        ]);

        if ($request->has('is_default')) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => Auth::id(),
            'label' => $request->label,
            'full_name' => $request->full_name,
            'mobile' => $request->mobile,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'district' => $request->district,
            'province' => $this->getProvinceFromDistrict($request->district),
            'postal_code' => $request->postal_code,
            'delivery_instructions' => $request->delivery_instructions,
            'is_default' => $request->has('is_default'),
        ]);

        return redirect()->route('mobile.account.addresses')->with('success', 'Address added!');
    }

    public function updateAddress(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'label' => 'required|string|max:50',
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
        ]);

        if ($request->has('is_default')) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $address->update([
            'label' => $request->label,
            'full_name' => $request->full_name,
            'mobile' => $request->mobile,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'district' => $request->district,
            'province' => $this->getProvinceFromDistrict($request->district),
            'delivery_instructions' => $request->delivery_instructions,
            'is_default' => $request->has('is_default'),
        ]);

        return redirect()->route('mobile.account.addresses')->with('success', 'Address updated!');
    }

    public function deleteAddress($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();
        return redirect()->route('mobile.account.addresses')->with('success', 'Address deleted!');
    }

    public function setDefault($id)
{
    $address = Address::where('user_id', Auth::id())->findOrFail($id);
    
    // Remove default from all addresses
    Address::where('user_id', Auth::id())->update(['is_default' => false]);
    
    // Set this address as default
    $address->update(['is_default' => true]);
    
    return redirect()->route('mobile.account.addresses')->with('success', 'Default address updated!');
}

    private function getProvinceFromDistrict($district)
    {
        $provinces = [
            'Colombo' => 'Western', 'Gampaha' => 'Western', 'Kalutara' => 'Western',
            'Kandy' => 'Central', 'Matale' => 'Central', 'Nuwara Eliya' => 'Central',
            'Galle' => 'Southern', 'Matara' => 'Southern', 'Hambantota' => 'Southern',
            'Jaffna' => 'Northern', 'Kilinochchi' => 'Northern', 'Mannar' => 'Northern',
            'Vavuniya' => 'Northern', 'Mullaitivu' => 'Northern',
            'Batticaloa' => 'Eastern', 'Ampara' => 'Eastern', 'Trincomalee' => 'Eastern',
            'Kurunegala' => 'North Western', 'Puttalam' => 'North Western',
            'Anuradhapura' => 'North Central', 'Polonnaruwa' => 'North Central',
            'Badulla' => 'Uva', 'Monaragala' => 'Uva',
            'Ratnapura' => 'Sabaragamuwa', 'Kegalle' => 'Sabaragamuwa',
        ];
        return $provinces[$district] ?? 'Western';
    }
}