<x-dashboard-layout>
    <x-slot name="title">My Products</x-slot>

    <div class="card bg-midnight-black border-0">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 class="h5">Products</h3>
                <div>
                    <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">Create Product</a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success py-2">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle mb-0">
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
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('vendor.products.edit', $it) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('vendor.products.destroy', $it) }}" onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </div>

</x-dashboard-layout>
