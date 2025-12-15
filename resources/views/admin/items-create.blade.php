<x-dashboard-layout>
    <x-slot name="title">Create Item</x-slot>

    <div class="card">
        <form method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label class="block mb-2 font-bold">Item Name</label>
                <input type="text" name="item_name" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Vendor</label>
                <select name="vendor_id" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                    <option value="">Select Vendor</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}">{{ $v->vendor_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Price</label>
                <input type="number" name="item_price" step="0.01" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Type</label>
                <select name="item_type" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                    <option value="jual">Jual</option>
                    <option value="sewa">Sewa</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Status</label>
                <select name="item_status" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Stock</label>
                <input type="number" name="item_stock" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" value="1" min="0" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Description</label>
                <textarea name="item_description" rows="4" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac"></textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Product Images</label>
                <input type="file" name="images[]" accept="image/*" multiple class="form-control" />
                <small class="text-muted">Optional — upload one or more images</small>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('admin.items') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-dashboard-layout>