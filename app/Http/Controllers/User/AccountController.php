<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('grand_total');
        
        return view('account.dashboard', compact('user', 'recentOrders', 'wishlistCount', 'totalOrders', 'totalSpent'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('account.orders', compact('orders'));
    }

    public function orderDetail($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with('items.product', 'shippingAddress')
            ->findOrFail($id);
        
        return view('account.order-detail', compact('order'));
    }

    public function wishlist()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with('product.images')
            ->get();
        
        return view('account.wishlist', compact('wishlist'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

public function updateProfile(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'mobile' => 'nullable|string|max:20',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240' // Increased to 10MB
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'mobile' => $request->mobile,
    ]);

    // Handle profile photo upload
    if ($request->hasFile('profile_photo')) {
        $file = $request->file('profile_photo');
        
        // Validate file
        if ($file && $file->isValid() && $file->getError() === UPLOAD_ERR_OK) {
            
            // Check file size again (in case of large files)
            if ($file->getSize() > 10 * 1024 * 1024) {
                return back()->with('error', 'File is too large. Maximum size is 10MB.');
            }
            
            // Delete old photo if exists
            if ($user->profile_photo) {
                $oldPath = storage_path('app/public/' . $user->profile_photo);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
                if (Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
            }
            
            // Create directory if not exists
            $uploadDir = storage_path('app/public/profiles');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Compress large images (over 1MB)
            $imageData = null;
            $extension = $file->getClientOriginalExtension();
            
            // If image is large (> 1MB), compress it
            if ($file->getSize() > 1024 * 1024 && in_array($extension, ['jpg', 'jpeg', 'png'])) {
                try {
                    // Create image resource
                    if ($extension === 'png') {
                        $imageData = imagecreatefrompng($file->getPathname());
                    } else {
                        $imageData = imagecreatefromjpeg($file->getPathname());
                    }
                    
                    if ($imageData) {
                        // Compress and save as JPEG
                        $extension = 'jpg';
                        $filename = time() . '_' . uniqid() . '.jpg';
                        $fullPath = $uploadDir . '/' . $filename;
                        
                        // Save compressed image (80% quality)
                        imagejpeg($imageData, $fullPath, 80);
                        imagedestroy($imageData);
                        
                        $user->profile_photo = 'profiles/' . $filename;
                        $user->save();
                        
                        return back()->with('success', 'Profile updated successfully! (Image compressed)');
                    }
                } catch (\Exception $e) {
                    // If compression fails, fall back to original
                    \Log::error('Image compression failed: ' . $e->getMessage());
                }
            }
            
            // If compression didn't happen or failed, use original file
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $file->move($uploadDir, $filename);
            
            $user->profile_photo = 'profiles/' . $filename;
            $user->save();
            
            return back()->with('success', 'Profile updated successfully!');
            
        } else {
            $errorMsg = 'File upload failed. ';
            if ($file) {
                $errorMsg .= 'Error code: ' . $file->getError();
            }
            return back()->with('error', $errorMsg);
        }
    }

    return back()->with('success', 'Profile updated successfully!');
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

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}