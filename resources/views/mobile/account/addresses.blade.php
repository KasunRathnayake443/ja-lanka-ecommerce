@extends('layouts.mobile')

@section('title', 'Address Book')

@section('content')
<div class="pb-20">
    
    <!-- Header -->
    <div class="bg-white border-b border-gray-100 px-5 py-4 sticky top-0 z-10">
        <div class="flex items-center gap-3">
            <a href="{{ route('mobile.account.dashboard') }}" class="text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Address Book</h1>
        </div>
    </div>
    
    <!-- Address List -->
    <div class="p-4 space-y-3">
        @forelse($addresses as $address)
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 relative">
            @if($address->is_default)
                <div class="absolute top-3 right-3">
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Default</span>
                </div>
            @endif
            
            <div class="flex items-center gap-2 mb-2">
                <span class="text-lg">{{ $address->label === 'Home' ? '🏠' : ($address->label === 'Work' ? '💼' : '📍') }}</span>
                <span class="font-semibold text-gray-800">{{ $address->label }}</span>
            </div>
            
            <p class="text-sm text-gray-600">{{ $address->full_name }}</p>
            <p class="text-sm text-gray-500">{{ $address->mobile }}</p>
            <p class="text-sm text-gray-600 mt-2">{{ $address->address_line1 }}</p>
            @if($address->address_line2)
                <p class="text-sm text-gray-600">{{ $address->address_line2 }}</p>
            @endif
            <p class="text-sm text-gray-600">{{ $address->city }}, {{ $address->district }}</p>
            @if($address->delivery_instructions)
                <p class="text-xs text-gray-500 mt-1 italic">📝 {{ $address->delivery_instructions }}</p>
            @endif
            
            <div class="flex gap-4 mt-3 pt-3 border-t border-gray-100">
                <button onclick="editAddress({{ json_encode($address) }})" class="text-blue-600 text-sm hover:text-blue-800">Edit</button>
                @if(!$address->is_default)
                    <form action="{{ route('mobile.account.address.set-default', $address->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="text-green-600 text-sm hover:text-green-800">Set as Default</button>
                    </form>
                @endif
                <button onclick="deleteAddress({{ $address->id }})" class="text-red-600 text-sm hover:text-red-800">Delete</button>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <p class="text-gray-500">No saved addresses</p>
            <button onclick="openAddressModal()" class="inline-block mt-3 text-red-600">Add New Address</button>
        </div>
        @endforelse
    </div>
    
    <!-- Add Address Button -->
    @if($addresses->count() > 0)
    <div class="px-4 pb-4">
        <button onclick="openAddressModal()" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
            + Add New Address
        </button>
    </div>
    @endif
    
    <!-- Address Modal -->
    <div id="addressModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-end justify-center">
        <div class="bg-white rounded-t-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white px-5 py-4 border-b flex justify-between items-center">
                <h3 id="modalTitle" class="text-lg font-semibold">Add New Address</h3>
                <button onclick="closeAddressModal()" class="text-gray-500 text-2xl">&times;</button>
            </div>
            
            <form id="addressForm" method="POST" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="address_id" id="addressId">
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <!-- Label Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Label *</label>
                    <select name="label" id="addressLabel" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 outline-none">
                        <option value="Home">🏠 Home</option>
                        <option value="Work">💼 Work</option>
                        <option value="Other">📍 Other</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                    <input type="text" name="full_name" id="addressFullName" required value="{{ Auth::user()->name }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number *</label>
                    <input type="tel" name="mobile" id="addressMobile" required value="{{ Auth::user()->mobile }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 outline-none">
                </div>
                
                <!-- Google Map Search (Mobile optimized) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Location</label>
                    <div class="flex gap-2">
                        <input type="text" id="searchAddress" placeholder="Type address to search..." 
                               class="flex-1 px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 outline-none">
                        <button type="button" onclick="searchAddress()" class="bg-gray-800 text-white px-4 py-3 rounded-lg text-sm hover:bg-gray-700">
                            Search
                        </button>
                    </div>
                </div>
                
                <!-- Google Map (Mobile friendly size) -->
                <div>
                    <div id="map" class="w-full h-48 rounded-lg border border-gray-200 mb-1"></div>
                    <p class="text-xs text-gray-500 text-center">Tap on map to select location</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1 *</label>
                    <input type="text" name="address_line1" id="addressLine1" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (Optional)</label>
                    <input type="text" name="address_line2" id="addressLine2"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                    <input type="text" name="city" id="addressCity" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">District *</label>
                    <select name="district" id="addressDistrict" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 outline-none">
                        <option value="">Select District</option>
                        <option value="Colombo">Colombo</option>
                        <option value="Gampaha">Gampaha</option>
                        <option value="Kalutara">Kalutara</option>
                        <option value="Kandy">Kandy</option>
                        <option value="Matale">Matale</option>
                        <option value="Nuwara Eliya">Nuwara Eliya</option>
                        <option value="Galle">Galle</option>
                        <option value="Matara">Matara</option>
                        <option value="Hambantota">Hambantota</option>
                        <option value="Jaffna">Jaffna</option>
                        <option value="Kilinochchi">Kilinochchi</option>
                        <option value="Mannar">Mannar</option>
                        <option value="Vavuniya">Vavuniya</option>
                        <option value="Mullaitivu">Mullaitivu</option>
                        <option value="Batticaloa">Batticaloa</option>
                        <option value="Ampara">Ampara</option>
                        <option value="Trincomalee">Trincomalee</option>
                        <option value="Kurunegala">Kurunegala</option>
                        <option value="Puttalam">Puttalam</option>
                        <option value="Anuradhapura">Anuradhapura</option>
                        <option value="Polonnaruwa">Polonnaruwa</option>
                        <option value="Badulla">Badulla</option>
                        <option value="Monaragala">Monaragala</option>
                        <option value="Ratnapura">Ratnapura</option>
                        <option value="Kegalle">Kegalle</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Province (Auto-filled)</label>
                    <input type="text" name="province" id="addressProvince" readonly
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                    <input type="text" name="postal_code" id="addressPostalCode"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Instructions</label>
                    <textarea name="delivery_instructions" id="addressInstructions" rows="2" 
                              class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-red-500 outline-none"
                              placeholder="e.g., Landmark, gate code, delivery notes"></textarea>
                </div>
                
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_default" id="addressDefault" value="1" class="w-4 h-4 accent-red-600">
                    <label for="addressDefault" class="text-sm text-gray-700">Set as default address</label>
                </div>
                
                <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                    Save Address
                </button>
            </form>
        </div>
    </div>
    
</div>

<script>
// Store route URLs
const addressStoreUrl = "{{ route('mobile.account.address.store') }}";
const addressUpdateUrl = "{{ route('mobile.account.address.update', ['id' => '__ID__']) }}";

let map, marker, geocoder;
let selectedLat = null, selectedLng = null;

// Initialize Google Map
function initMap() {
    const defaultLocation = { lat: 6.9271, lng: 79.8612 }; // Colombo
    
    map = new google.maps.Map(document.getElementById('map'), {
        center: defaultLocation,
        zoom: 13,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false
    });
    
    marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP
    });
    
    geocoder = new google.maps.Geocoder();
    
    // Update when marker is dragged
    marker.addListener('dragend', function(event) {
        selectedLat = event.latLng.lat();
        selectedLng = event.latLng.lng();
        reverseGeocode(selectedLat, selectedLng);
    });
    
    // Update when map is clicked
    map.addListener('click', function(event) {
        selectedLat = event.latLng.lat();
        selectedLng = event.latLng.lng();
        marker.setPosition(event.latLng);
        reverseGeocode(selectedLat, selectedLng);
    });
}

// Reverse geocode to get address from coordinates
function reverseGeocode(lat, lng) {
    const latlng = { lat: lat, lng: lng };
    geocoder.geocode({ location: latlng }, function(results, status) {
        if (status === 'OK' && results[0]) {
            const address = results[0].formatted_address;
            document.getElementById('addressLine1').value = address;
        }
    });
}

// Search address
function searchAddress() {
    const address = document.getElementById('searchAddress').value;
    if (!address) return;
    
    geocoder.geocode({ address: address }, function(results, status) {
        if (status === 'OK' && results[0]) {
            const location = results[0].geometry.location;
            selectedLat = location.lat();
            selectedLng = location.lng();
            map.setCenter(location);
            marker.setPosition(location);
            document.getElementById('addressLine1').value = results[0].formatted_address;
            
            // Extract address components
            const components = results[0].address_components;
            for (let comp of components) {
                if (comp.types.includes('locality')) {
                    document.getElementById('addressCity').value = comp.long_name;
                }
                if (comp.types.includes('administrative_area_level_2')) {
                    document.getElementById('addressDistrict').value = comp.long_name;
                }
                if (comp.types.includes('administrative_area_level_1')) {
                    document.getElementById('addressProvince').value = comp.long_name;
                }
                if (comp.types.includes('postal_code')) {
                    document.getElementById('addressPostalCode').value = comp.long_name;
                }
            }
            
            // Trigger district change to auto-fill province
            document.getElementById('addressDistrict').dispatchEvent(new Event('change'));
        } else {
            alert('Address not found. Please try again.');
        }
    });
}

// Auto-fill province based on district
document.getElementById('addressDistrict').addEventListener('change', function() {
    const district = this.value;
    const provinces = {
        'Colombo': 'Western', 'Gampaha': 'Western', 'Kalutara': 'Western',
        'Kandy': 'Central', 'Matale': 'Central', 'Nuwara Eliya': 'Central',
        'Galle': 'Southern', 'Matara': 'Southern', 'Hambantota': 'Southern',
        'Jaffna': 'Northern', 'Kilinochchi': 'Northern', 'Mannar': 'Northern',
        'Vavuniya': 'Northern', 'Mullaitivu': 'Northern',
        'Batticaloa': 'Eastern', 'Ampara': 'Eastern', 'Trincomalee': 'Eastern',
        'Kurunegala': 'North Western', 'Puttalam': 'North Western',
        'Anuradhapura': 'North Central', 'Polonnaruwa': 'North Central',
        'Badulla': 'Uva', 'Monaragala': 'Uva',
        'Ratnapura': 'Sabaragamuwa', 'Kegalle': 'Sabaragamuwa'
    };
    document.getElementById('addressProvince').value = provinces[district] || '';
});

function openAddressModal() {
    document.getElementById('addressModal').classList.remove('hidden');
    document.getElementById('addressModal').classList.add('flex');
    document.getElementById('modalTitle').innerText = 'Add New Address';
    document.getElementById('addressForm').action = addressStoreUrl;
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('addressId').value = '';
    document.getElementById('addressLabel').value = 'Home';
    document.getElementById('addressFullName').value = '{{ Auth::user()->name }}';
    document.getElementById('addressMobile').value = '{{ Auth::user()->mobile }}';
    document.getElementById('addressLine1').value = '';
    document.getElementById('addressLine2').value = '';
    document.getElementById('addressCity').value = '';
    document.getElementById('addressDistrict').value = '';
    document.getElementById('addressProvince').value = '';
    document.getElementById('addressPostalCode').value = '';
    document.getElementById('addressInstructions').value = '';
    document.getElementById('addressDefault').checked = false;
    document.getElementById('searchAddress').value = '';
    
    selectedLat = null;
    selectedLng = null;
    
    document.body.style.overflow = 'hidden';
}

function editAddress(address) {
    openAddressModal();
    document.getElementById('modalTitle').innerText = 'Edit Address';
    document.getElementById('addressForm').action = addressUpdateUrl.replace('__ID__', address.id);
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('addressId').value = address.id;
    document.getElementById('addressLabel').value = address.label;
    document.getElementById('addressFullName').value = address.full_name;
    document.getElementById('addressMobile').value = address.mobile;
    document.getElementById('addressLine1').value = address.address_line1;
    document.getElementById('addressLine2').value = address.address_line2 || '';
    document.getElementById('addressCity').value = address.city;
    document.getElementById('addressDistrict').value = address.district;
    document.getElementById('addressProvince').value = address.province || '';
    document.getElementById('addressPostalCode').value = address.postal_code || '';
    document.getElementById('addressInstructions').value = address.delivery_instructions || '';
    document.getElementById('addressDefault').checked = address.is_default;
    
    if (address.latitude && address.longitude) {
        selectedLat = parseFloat(address.latitude);
        selectedLng = parseFloat(address.longitude);
        if (map) {
            const location = { lat: selectedLat, lng: selectedLng };
            map.setCenter(location);
            marker.setPosition(location);
        }
    }
}

function closeAddressModal() {
    document.getElementById('addressModal').classList.add('hidden');
    document.getElementById('addressModal').classList.remove('flex');
    document.body.style.overflow = '';
}

function deleteAddress(id) {
    if (confirm('Are you sure you want to delete this address?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/mobile/account/address/${id}`;
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    if (typeof google !== 'undefined') {
        initMap();
    }
});
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>

@endsection