<x-dashboard-layout>
    <x-slot name="title">Admin — Advertisements</x-slot>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-900 bg-opacity-30 rounded-lg border border-green-500">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('admin.ads.create') }}" class="btn btn-primary">+ Create Ad</a>
    </div>

    <div class="card">
        <h3 class="text-xl font-bold mb-3">All Ads</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Vendor</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ads as $ad)
                        <tr>
                            <td>{{ $ad->ad_title ?? '—' }}</td>
                            <td>{{ optional($ad->vendor)->vendor_name }}</td>
                            <td>{{ $ad->ad_start_date ? \Carbon\Carbon::parse($ad->ad_start_date)->format('Y-m-d') : '—' }}</td>
                            <td>{{ $ad->ad_end_date ? \Carbon\Carbon::parse($ad->ad_end_date)->format('Y-m-d') : '—' }}</td>
                            <td>
                                <a href="{{ route('admin.ads.edit', $ad->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('admin.ads.destroy', $ad->id) }}" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-secondary btn-sm" onclick="return confirm('Delete this ad?')">Delete</button>
                                </form>
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