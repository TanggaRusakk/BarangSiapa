<x-dashboard-layout>
    <x-slot name="title">Edit Advertisement</x-slot>

    <div class="card">
        <h3 class="text-xl font-bold mb-4">Edit Ad</h3>

        <form method="POST" action="{{ route('vendor.ads.update', $ad->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <div class="mb-4">
                <label class="block mb-2 font-bold">Item</label>
                <select name="item_id" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac uniform-field" required>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ $ad->item_id == $item->id ? 'selected' : '' }}>
                            {{ $item->item_name }} ({{ ucfirst($item->item_type) }})
                        </option>
                    @endforeach
                </select>
                @error('item_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Ad Image</label>
                @if($ad->ad_image)
                    <div class="mb-2">
                        <img src="{{ asset('images/ads/' . $ad->ad_image) }}" alt="Current ad image" class="w-32 h-24 object-cover rounded border border-soft-lilac">
                    </div>
                @endif
                <input type="file" name="ad_image" accept="image/*" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition uniform-field file" style="background: rgba(9,9,15,0.6); color: #ffffff;">
                <p class="text-sm text-gray-400 mt-1">Optional. Leave empty to keep current image.</p>
                @error('ad_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Start Date</label>
                <input type="datetime-local" name="start_date" value="{{ $ad->start_date ? \Carbon\Carbon::parse($ad->start_date)->format('Y-m-d\TH:i') : '' }}" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none uniform-field vendor-ads-form" style="background: rgba(9,9,15,0.6); color: #ffffff;" required>
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">End Date</label>
                <input type="datetime-local" name="end_date" value="{{ $ad->end_date ? \Carbon\Carbon::parse($ad->end_date)->format('Y-m-d\TH:i') : '' }}" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none uniform-field vendor-ads-form" style="background: rgba(9,9,15,0.6); color: #ffffff;" required>
                @error('end_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Status</label>
                <select name="status" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac uniform-field" required>
                    <option value="active" {{ $ad->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $ad->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="expired" {{ $ad->status == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 p-3 rounded bg-gray-900 bg-opacity-30">
                <p class="text-sm text-gray-400">
                    <strong>Note:</strong> Ad price cannot be changed after payment. Original price: 
                    <span class="text-green-400">Rp {{ number_format($ad->price, 0, ',', '.') }}</span>
                </p>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Update Ad</button>
                <a href="{{ route('vendor.ads.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-dashboard-layout>
