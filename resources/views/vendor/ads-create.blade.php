<x-dashboard-layout>
    <x-slot name="title">Create Advertisement</x-slot>

    <div class="card">
        <h3 class="text-xl font-bold mb-4">Create New Ad - Payment Required</h3>
        <p class="text-gray-400 mb-6">To create an advertisement, you need to pay the ad fee first. After payment is confirmed, your ad will be created and activated.</p>

        <form method="POST" action="{{ route('vendor.ads.pay') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label class="block mb-2 font-bold">Select Item to Advertise</label>
                <select name="item_id" class="w-full px-4 py-3 bg-midnight-black bg-opacity-60 border-2 border-royal-purple border-opacity-40 rounded-lg text-white focus:border-neon-pink focus:ring-0 transition appearance-none uniform-field vendor-ads-form" style="background: rgba(9,9,15,0.6); color: #ffffff;" required>
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

            <div class="mb-4 p-4 rounded bg-cyan-900 bg-opacity-10 border border-cyan-500 border-opacity-30">
                <label class="block mb-2 font-bold">Total Payment</label>
                <div id="price-preview" class="text-2xl font-bold text-green-400 mb-2">Rp 0</div>
                <p class="text-sm text-gray-400">Price: Rp 50,000 per day (calculated automatically based on start and end dates)</p>
                <p class="text-xs text-gray-500 mt-1" id="days-info">Select start and end dates to calculate</p>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                <a href="{{ route('vendor.ads.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        const startDateInput = document.querySelector('input[name="start_date"]');
        const endDateInput = document.querySelector('input[name="end_date"]');
        const pricePreview = document.getElementById('price-preview');
        const daysInfo = document.getElementById('days-info');
        const pricePerDay = 50000;

        function calculatePrice() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (startDateInput.value && endDateInput.value && startDate < endDate) {
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // inclusive
                const totalPrice = diffDays * pricePerDay;

                pricePreview.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
                daysInfo.textContent = diffDays + ' day(s) × Rp 50,000 = Rp ' + totalPrice.toLocaleString('id-ID');
            } else {
                pricePreview.textContent = 'Rp 0';
                daysInfo.textContent = 'Select valid start and end dates';
            }
        }

        startDateInput.addEventListener('change', calculatePrice);
        endDateInput.addEventListener('change', calculatePrice);

        // (file input uses native browser UI here to match ads-edit)
    </script>
</x-dashboard-layout>
