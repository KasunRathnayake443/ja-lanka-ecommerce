<?php $__env->startSection('title', 'My Addresses - Ja Lanka'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-[1400px] mx-auto px-5 md:px-10 py-10">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar -->
        <aside class="lg:w-80">
            <?php echo $__env->make('account.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1">
            <div class="bg-white border border-gray-100 rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-light font-['Cormorant_Garamond']">My Addresses</h2>
                    <button onclick="openAddressModal()" class="bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800 transition text-sm">
                        + Add New Address
                    </button>
                </div>
                
                <?php if(session('success')): ?>
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                
                <?php if($addresses->count() > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-200 rounded-lg p-5 relative">
                            <?php if($address->is_default): ?>
                                <span class="absolute top-3 right-3 bg-red-100 text-red-700 text-xs px-2 py-1 rounded">Default</span>
                            <?php endif; ?>
                            
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg"><?php echo e($address->label === 'Home' ? '🏠' : ($address->label === 'Work' ? '💼' : '📍')); ?></span>
                                <h3 class="font-semibold text-gray-900"><?php echo e($address->label); ?></h3>
                            </div>
                            
                            <p class="text-sm text-gray-700"><?php echo e($address->full_name); ?></p>
                            <p class="text-sm text-gray-500"><?php echo e($address->mobile); ?></p>
                            <p class="text-sm text-gray-600 mt-2"><?php echo e($address->full_address); ?></p>
                            
                            <div class="flex gap-3 mt-4 pt-3 border-t border-gray-100">
                                <button onclick="editAddress(<?php echo e(json_encode($address)); ?>)" class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                                <?php if(!$address->is_default): ?>
                                    <form action="<?php echo e(route('account.addresses.set-default', $address->id)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm">Set as Default</button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?php echo e(route('account.addresses.destroy', $address->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Delete this address?')">Delete</button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-gray-500">No addresses saved yet</p>
                        <button onclick="openAddressModal()" class="mt-3 text-red-700 hover:underline">Add your first address</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    </div>
</div>

<!-- Address Modal -->
<div id="addressModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4" onclick="closeAddressModal(event)">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="sticky top-0 bg-white px-6 py-4 border-b flex justify-between items-center">
            <h3 id="modalTitle" class="text-xl font-semibold">Add New Address</h3>
            <button onclick="closeAddressModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        
        <form id="addressForm" method="POST" class="p-6 space-y-5">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="address_id" id="addressId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Label *</label>
                    <select name="label" id="addressLabel" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500">
                        <option value="Home">🏠 Home</option>
                        <option value="Work">💼 Work</option>
                        <option value="Other">📍 Other</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                    <input type="text" name="full_name" id="addressFullName" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500" value="<?php echo e(Auth::user()->name); ?>">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number *</label>
                    <input type="tel" name="mobile" id="addressMobile" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500" value="<?php echo e(Auth::user()->mobile); ?>">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Address on Map</label>
                    <div class="flex gap-2">
                        <input type="text" id="searchAddress" placeholder="Type address to search..." class="flex-1 border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500">
                        <button type="button" onclick="searchAddress()" class="bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800">Search</button>
                    </div>
                </div>
                
                <div class="md:col-span-2">
                    <div id="map" class="w-full h-64 rounded-lg border border-gray-200 mb-2"></div>
                    <p class="text-xs text-gray-500">Click on map to select location or search above</p>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1 *</label>
                    <input type="text" name="address_line1" id="addressLine1" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (Optional)</label>
                    <input type="text" name="address_line2" id="addressLine2" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                    <input type="text" name="city" id="addressCity" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">District *</label>
                    <input type="text" name="district" id="addressDistrict" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                    <input type="text" name="province" id="addressProvince" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                    <input type="text" name="postal_code" id="addressPostalCode" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-red-500">
                </div>
                
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_default" id="addressDefault" value="1" class="w-4 h-4 accent-red-600">
                    <label for="addressDefault" class="text-sm text-gray-700">Set as default address</label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeAddressModal()" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-red-700 text-white rounded-lg hover:bg-red-800">Save Address</button>
            </div>
        </form>
    </div>
</div>

<script>
// Define route URLs as JavaScript variables
const storeUrl = "<?php echo e(route('account.addresses.store')); ?>";
const updateUrl = "<?php echo e(route('account.addresses.update', ['id' => 'ADDRESS_ID'])); ?>";

let map, marker, geocoder;
let selectedLat = null, selectedLng = null;

// Initialize Google Map
function initMap() {
    const defaultLocation = { lat: 6.9271, lng: 79.8612 }; // Colombo
    
    map = new google.maps.Map(document.getElementById('map'), {
        center: defaultLocation,
        zoom: 12,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });
    
    marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP
    });
    
    geocoder = new google.maps.Geocoder();
    
    // Update coordinates when marker is dragged
    marker.addListener('dragend', function(event) {
        selectedLat = event.latLng.lat();
        selectedLng = event.latLng.lng();
        reverseGeocode(selectedLat, selectedLng);
    });
    
    // Update coordinates when map is clicked
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
            
            // Try to extract city and district from the address
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
        } else {
            alert('Address not found. Please try again.');
        }
    });
}

function openAddressModal() {
    document.getElementById('addressModal').classList.remove('hidden');
    document.getElementById('addressModal').classList.add('flex');
    document.getElementById('modalTitle').innerText = 'Add New Address';
    document.getElementById('addressForm').action = storeUrl;
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('addressId').value = '';
    document.getElementById('addressLabel').value = 'Home';
    document.getElementById('addressFullName').value = '<?php echo e(Auth::user()->name); ?>';
    document.getElementById('addressMobile').value = '<?php echo e(Auth::user()->mobile); ?>';
    document.getElementById('addressLine1').value = '';
    document.getElementById('addressLine2').value = '';
    document.getElementById('addressCity').value = '';
    document.getElementById('addressDistrict').value = '';
    document.getElementById('addressProvince').value = '';
    document.getElementById('addressPostalCode').value = '';
    document.getElementById('addressDefault').checked = false;
    document.getElementById('searchAddress').value = '';
    
    selectedLat = null;
    selectedLng = null;
}

function editAddress(address) {
    openAddressModal();
    document.getElementById('modalTitle').innerText = 'Edit Address';
    // Replace ADDRESS_ID placeholder with actual ID
    document.getElementById('addressForm').action = updateUrl.replace('ADDRESS_ID', address.id);
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
    
    if (address.latitude && address.longitude) {
        selectedLat = parseFloat(address.latitude);
        selectedLng = parseFloat(address.longitude);
        const location = { lat: selectedLat, lng: selectedLng };
        if (map) {
            map.setCenter(location);
            marker.setPosition(location);
        }
    }
}

function closeAddressModal(event) {
    if (!event || event.target === document.getElementById('addressModal')) {
        document.getElementById('addressModal').classList.add('hidden');
        document.getElementById('addressModal').classList.remove('flex');
    }
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    if (typeof google !== 'undefined') {
        initMap();
    }
});
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(env('GOOGLE_MAPS_API_KEY')); ?>&callback=initMap" async defer></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.desktop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\Herd\ja-lanka-ecommerce\resources\views/account/addresses.blade.php ENDPATH**/ ?>