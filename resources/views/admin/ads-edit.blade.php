<x-dashboard-layout>
    <x-slot name="title">Edit Advertisement</x-slot>

    <div class="card">
        <form method="POST" action="{{ route('admin.ads.update', $ad->id) }}">
            @csrf
            @method('PATCH')
            
            <div class="mb-4">
                <label class="block mb-2 font-bold">Item</label>
                <select name="item_id" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ $ad->item_id == $item->id ? 'selected' : '' }}>
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
                <input type="datetime-local" name="start_date" value="{{ $ad->start_date ? \Carbon\Carbon::parse($ad->start_date)->format('Y-m-d\TH:i') : '' }}" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">End Date</label>
                <input type="datetime-local" name="end_date" value="{{ $ad->end_date ? \Carbon\Carbon::parse($ad->end_date)->format('Y-m-d\TH:i') : '' }}" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                @error('end_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Price</label>
                <input type="number" name="price" value="{{ $ad->price }}" min="0" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Status</label>
                <select name="status" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac" required>
                    <option value="active" {{ $ad->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $ad->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="expired" {{ $ad->status == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.ads') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-dashboard-layout>