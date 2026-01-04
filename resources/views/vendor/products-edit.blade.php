<x-dashboard-layout>
    <x-slot name="title">Edit Product</x-slot>

    <div class="card max-w-3xl">
        <form method="POST" action="{{ route('vendor.products.update', $item) }}" class="mt-6 space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div>
                <x-input-label for="item_name" :value="__('Product Name')" />
                <x-text-input id="item_name" name="item_name" type="text" class="mt-1 block w-full uniform-field" value="{{ old('item_name', $item->item_name) }}" required />
                <x-input-error class="mt-2" :messages="$errors->get('item_name')" />
            </div>

            <div>
                <x-input-label for="item_description" :value="__('Description')" />
                <textarea id="item_description" name="item_description" rows="4" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition uniform-field">{{ old('item_description', $item->item_description) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('item_description')" />
            </div>

            <!-- Current Gallery Images -->
            <div>
                <x-input-label :value="__('Current Gallery Images')" />
                @if($item->galleries && $item->galleries->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mt-2 gallery-grid">
                        @foreach($item->galleries as $gallery)
                            <div class="relative group gallery-item">
                                <img src="{{ $gallery->url }}" alt="Product Image" class="w-full h-24 object-cover rounded-lg border-2 border-royal-purple border-opacity-40">
                                <div class="mt-2 text-center">
                                    <button type="button"
                                            onclick="deleteGalleryImage({{ $gallery->id }})"
                                            class="remove-btn-below"
                                            title="Remove image">
                                        Remove image
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-3 mt-2 gallery-grid">
                        <div class="relative">
                            <img src="{{ asset('images/items/item_placeholder.jpg') }}" alt="Default placeholder" class="w-full h-24 object-cover rounded-lg border-2 border-royal-purple border-opacity-40">
                        </div>
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="image" :value="__('Add New Image')" />
                <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-neon-pink file:text-black uniform-field file" />
                <p class="text-sm text-soft-lilac mt-2">Upload a new image to add to the product gallery.</p>
                <x-input-error class="mt-2" :messages="$errors->get('image')" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="item_price" :value="__('Price')" />
                    <x-text-input id="item_price" name="item_price" type="number" class="mt-1 block w-full uniform-field" value="{{ old('item_price', $item->item_price) }}" required />
                    <x-input-error class="mt-2" :messages="$errors->get('item_price')" />
                </div>

                <div>
                    <x-input-label for="item_type" :value="__('Type')" />
                    <select id="item_type" name="item_type" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none uniform-field" required>
                        <option value="buy" {{ old('item_type', $item->item_type) === 'buy' ? 'selected' : '' }}>Buy</option>
                        <option value="rent" {{ old('item_type', $item->item_type) === 'rent' ? 'selected' : '' }}>Rent</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('item_type')" />
                </div>
            </div>

            <!-- Rental Duration Fields (shown when type is Rent) -->
            <div id="rentalFields" style="display: none;">
                <div class="p-4 rounded" style="background: rgba(106, 56, 194, 0.1); border: 1px solid rgba(106, 56, 194, 0.3);">
                    <h6 class="fw-bold mb-3" style="color: #C8A2C8;">⏱️ Rental Duration Settings</h6>
                    <p class="text-muted small mb-3">Set the duration and unit for rental pricing</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="rental_duration_value" :value="__('Duration Value')" />
                            <x-text-input id="rental_duration_value" name="rental_duration_value" type="number" class="mt-1 block w-full uniform-field" value="{{ old('rental_duration_value', $item->rental_duration_value) }}" min="1" placeholder="e.g., 1" />
                            <small class="text-muted d-block mt-1">Enter number (e.g., 1, 2, 3)</small>
                            <x-input-error class="mt-2" :messages="$errors->get('rental_duration_value')" />
                        </div>
                        <div class="col-md-6">
                            <x-input-label for="rental_duration_unit" :value="__('Duration Unit')" />
                            <select id="rental_duration_unit" name="rental_duration_unit" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none uniform-field">
                                <option value="day" {{ old('rental_duration_unit', $item->rental_duration_unit) === 'day' ? 'selected' : '' }}>Per Day (Hari)</option>
                                <option value="week" {{ old('rental_duration_unit', $item->rental_duration_unit) === 'week' ? 'selected' : '' }}>Per Week (Minggu)</option>
                                <option value="month" {{ old('rental_duration_unit', $item->rental_duration_unit) === 'month' ? 'selected' : '' }}>Per Month (Bulan)</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('rental_duration_unit')" />
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">💡 Example: If price is Rp 100,000 with 1 Day, customers pay Rp 100,000 per day</small>
                </div>
            </div>

            <div class="mb-8" style="margin-bottom:2.5rem;">
                <x-input-label for="item_status" :value="__('Status')" />
                <select id="item_status" name="item_status" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none uniform-field">
                    <option value="available" {{ old('item_status', $item->item_status) === 'available' ? 'selected' : '' }}>available</option>
                    <option value="unavailable" {{ old('item_status', $item->item_status) === 'unavailable' ? 'selected' : '' }}>unavailable</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('item_status')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Update Product') }}</x-primary-button>
                <a href="{{ route('vendor.products.list') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Show/hide rental fields based on item type
            const itemTypeSelect = document.getElementById('item_type');
            const rentalFields = document.getElementById('rentalFields');

            if (itemTypeSelect && rentalFields) {
                function toggleRentalFields() {
                    if (itemTypeSelect.value === 'rent') {
                        rentalFields.style.display = 'block';
                    } else {
                        rentalFields.style.display = 'none';
                    }
                }

                itemTypeSelect.addEventListener('change', toggleRentalFields);
                toggleRentalFields(); // Run on page load
            }

            // Delete gallery image function
            window.deleteGalleryImage = function (galleryId) {
                if (confirm('Are you sure you want to delete this image?')) {
                    // Create a hidden form and submit it
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/vendor/gallery/${galleryId}`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            };
        });
    </script>
    @endpush

</x-dashboard-layout>
