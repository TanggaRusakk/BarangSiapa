<x-dashboard-layout>
    <x-slot name="title">Add Product</x-slot>

    <div class="card max-w-3xl">
        <form method="POST" action="{{ route('vendor.products.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
            @csrf

            <div>
                <x-input-label for="item_name" :value="__('Product Name')" />
                <x-text-input id="item_name" name="item_name" type="text" class="mt-1 block w-full" required />
                <x-input-error class="mt-2" :messages="$errors->get('item_name')" />
            </div>

            <div>
                <x-input-label for="item_description" :value="__('Description')" />
                <textarea id="item_description" name="item_description" rows="4" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 focus:shadow-neon-pink transition appearance-none" style="background: rgba(9,9,15,0.6); color: #ffffff;"> </textarea>
                <x-input-error class="mt-2" :messages="$errors->get('item_description')" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="item_price" :value="__('Price')" />
                    <x-text-input id="item_price" name="item_price" type="number" class="mt-1 block w-full" required />
                    <x-input-error class="mt-2" :messages="$errors->get('item_price')" />
                </div>

                <div>
                    <x-input-label for="item_type" :value="__('Type')" />
                    <select id="item_type" name="item_type" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none" style="background: rgba(9,9,15,0.6); color: #ffffff;">
                            <option value="jual">Jual</option>
                            <option value="sewa">Sewa</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('item_type')" />
                </div>
            </div>

            <div>
                <x-input-label for="item_stock" :value="__('Stock')" />
                <x-text-input id="item_stock" name="item_stock" type="number" class="mt-1 block w-full" value="1" min="0" required />
                <x-input-error class="mt-2" :messages="$errors->get('item_stock')" />
            </div>

            <div>
                <x-input-label for="images" :value="__('Product Images')" />
                <input id="images" name="images[]" type="file" accept="image/*" multiple class="form-control" />
                <small class="text-muted">You can upload multiple images (optional).</small>
                <x-input-error class="mt-2" :messages="$errors->get('images')" />
                <x-input-error class="mt-2" :messages="$errors->get('images.*')" />
            </div>

            <div>
                <x-input-label for="item_status" :value="__('Status')" />
                <select id="item_status" name="item_status" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none" style="background: rgba(9,9,15,0.6); color: #ffffff;">
                    <option value="available">available</option>
                    <option value="unavailable">unavailable</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('item_status')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Create Product') }}</x-primary-button>
                <a href="{{ route('vendor.products.list') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

</x-dashboard-layout>
