<x-dashboard-layout>
    <x-slot name="title">Create Advertisement</x-slot>

    <div class="card">
        <h3 class="text-xl font-bold mb-4">Create New Ad - Payment Required</h3>
        <p class="text-gray-400 mb-6">To create an advertisement, you need to pay the ad fee first. After payment is confirmed, your ad will be created and activated.</p>

        <form method="POST" action="{{ route('vendor.ads.pay') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label class="block mb-2 font-bold">Select Item to Advertise</label>
                <select name="item_id" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac uniform-field" required>
                    <option value="">Choose an item...</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->item_name }} ({{ ucfirst($item->item_type) }})</option>
                    @endforeach
                </select>
                @error('item_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Start Date</label>
                <input type="datetime-local" name="start_date" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none uniform-field vendor-ads-form" style="background: rgba(9,9,15,0.6); color: #ffffff;" required>
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">End Date</label>
                <input type="datetime-local" name="end_date" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none uniform-field vendor-ads-form" style="background: rgba(9,9,15,0.6); color: #ffffff;" required>
                @error('end_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Ad Image</label>
                <input type="file" name="ad_image" accept="image/*" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition uniform-field file" style="background: rgba(9,9,15,0.6); color: #ffffff;">
                <p class="text-sm text-gray-400 mt-1">Optional. If left empty a default image will be used.</p>
                @error('ad_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold">Ad Price (Payment Amount)</label>
                <input type="number" name="ad_price" min="1000" step="1000" value="50000" class="w-full p-2 rounded bg-purple-900 bg-opacity-20 border border-soft-lilac uniform-field" required>
                <p class="text-sm text-gray-400 mt-1">This is the amount you will pay to run this advertisement.</p>
                @error('ad_price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                <a href="{{ route('vendor.ads.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-dashboard-layout>
