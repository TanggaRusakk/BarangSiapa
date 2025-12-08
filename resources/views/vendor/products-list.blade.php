<x-dashboard-layout>
    <x-slot name="title">My Products</x-slot>

    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold">Products</h3>
            <div>
                <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">Create Product</a>
            </div>
        </div>
        @if(session('success'))
            <div class="p-3 bg-green-900 bg-opacity-20 rounded mb-3">{{ session('success') }}</div>
        @endif
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $it)
                        <tr>
                            <td>{{ $it->item_name }}</td>
                            <td>Rp{{ number_format($it->item_price) }}</td>
                            <td>{{ ucfirst($it->item_status ?? '—') }}</td>
                            <td class="flex items-center gap-2">
                                <a href="{{ route('vendor.products.edit', $it) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('vendor.products.destroy', $it) }}" onsubmit="return confirm('Delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $items->links() }}</div>
    </div>

</x-dashboard-layout>
