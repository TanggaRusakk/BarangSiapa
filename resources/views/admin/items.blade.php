<x-dashboard-layout>
    <x-slot name="title">Admin — Items/Products</x-slot>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-900 bg-opacity-30 rounded-lg border border-green-500">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('admin.items.create') }}" class="btn btn-primary">+ Create Item</a>
    </div>

    <div class="card">
        <h3 class="text-xl font-bold mb-3">All Items</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Vendor</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ optional($item->vendor)->vendor_name }}</td>
                            <td>Rp{{ number_format($item->item_price, 0) }}</td>
                            <td>{{ ucfirst($item->item_type ?? 'sell') }}</td>
                            <td>{{ ucfirst($item->item_status ?? 'available') }}</td>
                            <td>
                                <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('admin.items.destroy', $item->id) }}" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-secondary btn-sm" onclick="return confirm('Delete this item?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
</x-dashboard-layout>