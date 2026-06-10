<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())->orderBy('is_default', 'desc')->get();
        return view('account.addresses', compact('addresses'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        
        // If this is the first address or marked as default, set as default
        $isDefault = $request->has('is_default') || Address::where('user_id', Auth::id())->count() === 0;
        
        if ($isDefault) {
            // Remove default from other addresses
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
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_default' => $isDefault,
        ]);
        
        return redirect()->route('account.addresses')->with('success', 'Address added successfully!');
    }
    
    public function update(Request $request, $id)
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
        
        $address->update([
            'label' => $request->label,
            'full_name' => $request->full_name,
            'mobile' => $request->mobile,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'district' => $request->district,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
        
        if ($request->has('set_default')) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
            $address->update(['is_default' => true]);
        }
        
        return redirect()->route('account.addresses')->with('success', 'Address updated successfully!');
    }
    
    public function destroy($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();
        
        return redirect()->route('account.addresses')->with('success', 'Address deleted successfully!');
    }
    
    public function setDefault($id)
    {
        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        Address::where('user_id', Auth::id())->where('id', $id)->update(['is_default' => true]);
        
        return redirect()->route('account.addresses')->with('success', 'Default address updated!');
    }
    
    public function geocode(Request $request)
    {
        $address = $request->get('address');
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => $apiKey,
        ]);
        
        if ($response->successful() && count($response->json()['results']) > 0) {
            $location = $response->json()['results'][0]['geometry']['location'];
            return response()->json([
                'success' => true,
                'lat' => $location['lat'],
                'lng' => $location['lng'],
                'formatted_address' => $response->json()['results'][0]['formatted_address']
            ]);
        }
        
        return response()->json(['success' => false]);
    }
}