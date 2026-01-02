<x-dashboard-layout>
    <x-slot name="title">Create Advertisement</x-slot>

    <div class="card">
        <form method="POST" action="{{ route('admin.ads.store') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block mb-2 font-bold">Item</label>
                <select name="item_id" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                    <option value="">Select Item</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->item_name }} - {{ $item->vendor->vendor_name ?? 'No Vendor' }}
                        </option>
                    @endforeach
                </select>
                @error('item_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Start Date</label>
                <input type="datetime-local" name="start_date" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">End Date</label>
                <input type="datetime-local" name="end_date" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                @error('end_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Price</label>
                <input type="number" name="price" min="0" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Status</label>
                <select name="status" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="expired">Expired</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('admin.ads') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-dashboard-layout>