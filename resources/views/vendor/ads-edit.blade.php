<x-dashboard-layout>
    <x-slot name="title">Edit Advertisement</x-slot>

    <div class="card">
        <h3 class="text-xl font-bold mb-4">Edit Ad</h3>

        <form method="POST" action="{{ route('vendor.ads.update', $ad->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <div class="mb-4">
                <label class="block mb-2 font-bold">Item</label>
                <div class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white transition appearance-none" style="background: rgba(9,9,15,0.6); color: #ffffff;">
                    {{ $ad->item->item_name }} ({{ ucfirst($ad->item->item_type) }})
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Ad Image</label>
                @if($ad->ad_image)
                    <div class="mb-2">
                        <img src="{{ asset('images/ads/' . $ad->ad_image) }}" alt="Current ad image" class="w-32 h-24 object-cover rounded border border-soft-lilac">
                    </div>
                @endif
                <input type="file" name="ad_image" accept="image/*" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none uniform-field vendor-ads-form" style="background: rgba(9,9,15,0.6); color: #ffffff;">
                <p class="text-sm text-gray-400 mt-1">Optional. Leave empty to keep current image.</p>
                @error('ad_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Start Date</label>
                <div class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white transition appearance-none" style="background: rgba(9,9,15,0.6); color: #ffffff;">
                    {{ $ad->start_date ? \Carbon\Carbon::parse($ad->start_date)->format('d M Y, H:i') : '-' }}
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">End Date</label>
                <div class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white transition appearance-none" style="background: rgba(9,9,15,0.6); color: #ffffff;">
                    {{ $ad->end_date ? \Carbon\Carbon::parse($ad->end_date)->format('d M Y, H:i') : '-' }}
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Status</label>
                <div class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white transition appearance-none" style="background: rgba(9,9,15,0.6); color: #ffffff;">
                    {{ ucfirst($ad->status) }}
                </div>
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
