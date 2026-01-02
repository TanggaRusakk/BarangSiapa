<x-dashboard-layout>
    <x-slot name="title">Add Product</x-slot>

    <div class="card max-w-3xl">
        <form method="POST" action="{{ route('vendor.products.store') }}" class="mt-6 space-y-4" enctype="multipart/form-data">
            @csrf

            <div>
                <x-input-label for="item_name" :value="__('Product Name')" />
                <x-text-input id="item_name" name="item_name" type="text" class="mt-1 block w-full uniform-field" required />
                <x-input-error class="mt-2" :messages="$errors->get('item_name')" />
            </div>

            <div>
                <x-input-label for="item_description" :value="__('Description')" />
                <textarea id="item_description" name="item_description" rows="4" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 focus:shadow-neon-pink transition appearance-none uniform-field" style="background: rgba(9,9,15,0.6); color: #ffffff;"> </textarea>
                <x-input-error class="mt-2" :messages="$errors->get('item_description')" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="item_price" :value="__('Price')" />
                    <x-text-input id="item_price" name="item_price" type="number" class="mt-1 block w-full uniform-field" required />
                    <x-input-error class="mt-2" :messages="$errors->get('item_price')" />
                </div>

                <div>
                    <x-input-label for="item_type" :value="__('Type')" />
                    <select id="item_type" name="item_type" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none uniform-field" style="background: rgba(9,9,15,0.6); color: #ffffff;" required>
                            <option value="jual">Jual (Sell)</option>
                            <option value="sewa">Sewa (Rent)</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('item_type')" />
                </div>
            </div>

            <!-- Rental Duration Fields (shown when type is Sewa) -->
            <div id="rentalFields" style="display: none;">
                <div class="p-4 rounded" style="background: rgba(106, 56, 194, 0.1); border: 1px solid rgba(106, 56, 194, 0.3);">
                    <h6 class="fw-bold mb-3" style="color: #C8A2C8;">⏱️ Rental Duration Settings</h6>
                    <p class="text-muted small mb-3">Set the duration and unit for rental pricing</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="rental_duration_value" :value="__('Duration Value')" />
                            <x-text-input id="rental_duration_value" name="rental_duration_value" type="number" class="mt-1 block w-full uniform-field" min="1" placeholder="e.g., 1" />
                            <small class="text-muted d-block mt-1">Enter number (e.g., 1, 2, 3)</small>
                            <x-input-error class="mt-2" :messages="$errors->get('rental_duration_value')" />
                        </div>
                        <div class="col-md-6">
                            <x-input-label for="rental_duration_unit" :value="__('Duration Unit')" />
                            <select id="rental_duration_unit" name="rental_duration_unit" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none uniform-field" style="background: rgba(9,9,15,0.6); color: #ffffff;">
                                <option value="day">Per Day (Hari)</option>
                                <option value="week">Per Week (Minggu)</option>
                                <option value="month">Per Month (Bulan)</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('rental_duration_unit')" />
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">💡 Example: If price is Rp 100,000 with 1 Day, customers pay Rp 100,000 per day</small>
                </div>
            </div>

            <div>
                <x-input-label for="item_stock" :value="__('Stock')" />
                <x-text-input id="item_stock" name="item_stock" type="number" class="mt-1 block w-full uniform-field" value="1" min="0" required />
                <x-input-error class="mt-2" :messages="$errors->get('item_stock')" />
            </div>

            <div>
                <x-input-label for="images" :value="__('Product Images')" />
                <input id="images" name="images[]" type="file" accept="image/*" multiple class="form-control uniform-field file" />
                <small class="text-muted">You can upload multiple images (optional).</small>
                <x-input-error class="mt-2" :messages="$errors->get('images')" />
                <x-input-error class="mt-2" :messages="$errors->get('images.*')" />
            </div>

            <div class="mb-8" style="margin-bottom:2.5rem;">
                <x-input-label for="item_status" :value="__('Status')" />
                <select id="item_status" name="item_status" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none uniform-field" style="background: rgba(9,9,15,0.6); color: #ffffff;">
                    <option value="available">available</option>
                    <option value="unavailable">unavailable</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('item_status')" />
            </div>

            <div class="flex items-center gap-4 mt-8">
                <x-primary-button>{{ __('Create Product') }}</x-primary-button>
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

            if (!itemTypeSelect || !rentalFields) return;

            function toggleRentalFields() {
                if (itemTypeSelect.value === 'sewa') {
                    rentalFields.style.display = 'block';
                } else {
                    rentalFields.style.display = 'none';
                }
            }

            itemTypeSelect.addEventListener('change', toggleRentalFields);
            toggleRentalFields(); // Run on page load
        });
    </script>
    @endpush

</x-dashboard-layout>
