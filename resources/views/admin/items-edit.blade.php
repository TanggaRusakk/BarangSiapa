<x-dashboard-layout>
    <x-slot name="title">Edit Item</x-slot>

    <div class="card">
        <form method="POST" action="{{ route('admin.items.update', $item->id) }}">
            @csrf
            @method('PATCH')
            
            <div class="mb-4">
                <label class="block mb-2 font-bold">Item Name</label>
                <input type="text" name="item_name" value="{{ $item->item_name }}" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Vendor</label>
                <select name="vendor_id" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}" {{ $item->vendor_id == $v->id ? 'selected' : '' }}>
                            {{ $v->vendor_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Price</label>
                <input type="number" name="item_price" value="{{ $item->item_price }}" step="0.01" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Type</label>
                <select name="item_type" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                    <option value="sell" {{ $item->item_type == 'sell' ? 'selected' : '' }}>Sell</option>
                    <option value="sewa" {{ $item->item_type == 'sewa' ? 'selected' : '' }}>Rent</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Status</label>
                <select name="item_status" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                    <option value="available" {{ $item->item_status == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ $item->item_status == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Description</label>
                <textarea name="item_description" rows="4" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac">{{ $item->item_description }}</textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.items') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-dashboard-layout>