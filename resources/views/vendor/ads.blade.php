<x-dashboard-layout>
    <x-slot name="title">My Advertisements</x-slot>

    <div class="mb-4">
        <a href="{{ route('vendor.ads.create') }}" class="btn btn-primary">Create New Ad</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4 p-3 rounded bg-green-900 bg-opacity-20 border border-green-500 text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4 p-3 rounded bg-red-900 bg-opacity-20 border border-red-500 text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <h3 class="text-xl font-bold mb-4">Your Advertisements</h3>

        @if($ads->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-soft-lilac border-opacity-30">
                            <th class="text-left p-3">Item</th>
                            <th class="text-left p-3">Start Date</th>
                            <th class="text-left p-3">End Date</th>
                            <th class="text-left p-3">Price</th>
                            <th class="text-left p-3">Status</th>
                            <th class="text-left p-3">Payment</th>
                            <th class="text-left p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ads as $ad)
                            <tr class="border-b border-soft-lilac border-opacity-10">
                                <td class="p-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $ad->ad_image ? asset('images/ads/' . $ad->ad_image) : asset('images/ads/ad_placeholder.jpg') }}" alt="ad image" style="width:64px;height:48px;object-fit:cover;border-radius:6px;border:1px solid rgba(255,255,255,0.04);">
                                        <div>{{ $ad->item->item_name }}</div>
                                    </div>
                                </td>
                                <td class="p-3">{{ \Carbon\Carbon::parse($ad->start_date)->format('Y-m-d H:i') }}</td>
                                <td class="p-3">{{ \Carbon\Carbon::parse($ad->end_date)->format('Y-m-d H:i') }}</td>
                                <td class="p-3">Rp {{ number_format($ad->price, 0, ',', '.') }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded text-xs 
                                        {{ $ad->status === 'active' ? 'bg-green-900 bg-opacity-30 text-green-300' : '' }}
                                        {{ $ad->status === 'inactive' ? 'bg-gray-900 bg-opacity-30 text-gray-300' : '' }}
                                        {{ $ad->status === 'expired' ? 'bg-red-900 bg-opacity-30 text-red-300' : '' }}">
                                        {{ ucfirst($ad->status) }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    @if($ad->payment)
                                        <span class="px-2 py-1 rounded text-xs bg-green-900 bg-opacity-30 text-green-300">
                                            {{ ucfirst($ad->payment->payment_status) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('vendor.ads.edit', $ad->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                                        <form method="POST" action="{{ route('vendor.ads.destroy', $ad->id) }}" onsubmit="return confirm('Delete this ad?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background: #ff3b30; color: white;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $ads->links() }}
            </div>
        @else
            <p class="text-gray-400">You haven't created any ads yet.</p>
        @endif
    </div>
</x-dashboard-layout>
