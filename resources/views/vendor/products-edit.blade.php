<x-dashboard-layout>
    <x-slot name="title">Edit Product</x-slot>

    <div class="card max-w-3xl">
        <form method="POST" action="{{ route('vendor.products.update', $item) }}" class="mt-6 space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div>
                <x-input-label for="item_name" :value="__('Product Name')" />
                <x-text-input id="item_name" name="item_name" type="text" class="mt-1 block w-full" value="{{ old('item_name', $item->item_name) }}" required />
                <x-input-error class="mt-2" :messages="$errors->get('item_name')" />
            </div>

            <div>
                <x-input-label for="item_description" :value="__('Description')" />
                <textarea id="item_description" name="item_description" rows="4" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition">{{ old('item_description', $item->item_description) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('item_description')" />
            </div>

            <div>
                <x-input-label for="image" :value="__('Product Image')" />
                <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-neon-pink file:text-black" />
                <p class="text-sm text-soft-lilac mt-2">Upload a new image to add to the product gallery.</p>
                <x-input-error class="mt-2" :messages="$errors->get('image')" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="item_price" :value="__('Price')" />
                    <x-text-input id="item_price" name="item_price" type="number" class="mt-1 block w-full" value="{{ old('item_price', $item->item_price) }}" required />
                    <x-input-error class="mt-2" :messages="$errors->get('item_price')" />
                </div>

                <div>
                    <x-input-label for="item_type" :value="__('Type')" />
                    <select id="item_type" name="item_type" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none">
                        <option value="jual" {{ old('item_type', $item->item_type) === 'jual' ? 'selected' : '' }}>Jual</option>
                        <option value="sewa" {{ old('item_type', $item->item_type) === 'sewa' ? 'selected' : '' }}>Sewa</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('item_type')" />
                </div>
            </div>

            <div>
                <x-input-label for="item_status" :value="__('Status')" />
                <select id="item_status" name="item_status" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none">
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

</x-dashboard-layout>
