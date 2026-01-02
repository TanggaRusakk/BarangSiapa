<x-dashboard-layout>
    <x-slot name="title">Admin — Advertisements (View Only)</x-slot>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-900 bg-opacity-30 rounded-lg border border-green-500">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4 p-4 rounded bg-cyan-900 bg-opacity-20 border border-cyan-500">
        <p class="text-cyan-300">
            <strong>Note:</strong> Vendors now manage their own advertisements. This page is view-only for admin monitoring.
        </p>
    </div>

    <div class="card">
        <h3 class="text-xl font-bold mb-3">All Advertisements</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Vendor</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ads as $ad)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $ad->ad_image ? asset('images/ads/' . $ad->ad_image) : asset('images/ads/ad_placeholder.jpg') }}" alt="ad image" style="width:64px;height:48px;object-fit:cover;border-radius:6px;border:1px solid rgba(255,255,255,0.04);">
                                    <div>{{ optional($ad->item)->item_name ?? '—' }}</div>
                                </div>
                            </td>
                            <td>{{ optional(optional($ad->item)->vendor)->vendor_name ?? '—' }}</td>
                            <td>{{ $ad->start_date ? \Carbon\Carbon::parse($ad->start_date)->format('Y-m-d H:i') : '—' }}</td>
                            <td>{{ $ad->end_date ? \Carbon\Carbon::parse($ad->end_date)->format('Y-m-d H:i') : '—' }}</td>
                            <td>Rp {{ number_format($ad->price ?? 0, 0, ',', '.') }}</td>
                            <td>
                                <span class="px-2 py-1 rounded text-xs 
                                    {{ $ad->status === 'active' ? 'bg-green-900 bg-opacity-30 text-green-300' : '' }}
                                    {{ $ad->status === 'inactive' ? 'bg-gray-900 bg-opacity-30 text-gray-300' : '' }}
                                    {{ $ad->status === 'expired' ? 'bg-red-900 bg-opacity-30 text-red-300' : '' }}">
                                    {{ ucfirst($ad->status ?? '—') }}
                                </span>
                            </td>
                            <td>
                                @if($ad->payment)
                                    <span class="px-2 py-1 rounded text-xs 
                                        {{ $ad->payment->payment_status === 'settlement' ? 'bg-green-900 bg-opacity-30 text-green-300' : 'bg-yellow-900 bg-opacity-30 text-yellow-300' }}">
                                        {{ ucfirst($ad->payment->payment_status) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $ads->links() }}
        </div>
    </div>
</x-dashboard-layout>