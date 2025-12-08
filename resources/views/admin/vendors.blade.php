<x-dashboard-layout>
    <x-slot name="title">Admin — Vendors</x-slot>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-900 bg-opacity-30 rounded-lg border border-green-500">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">+ Create Vendor</a>
    </div>

    <div class="card">
        <h3 class="text-xl font-bold mb-3">All Vendors</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>User</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendors as $vendor)
                        <tr>
                            <td>{{ $vendor->vendor_name }}</td>
                            <td>{{ optional($vendor->user)->email }}</td>
                            <td>{{ $vendor->location }}</td>
                            <td>
                                <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('admin.vendors.destroy', $vendor->id) }}" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-secondary btn-sm" onclick="return confirm('Delete this vendor?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $vendors->links() }}
        </div>
    </div>
</x-dashboard-layout>