<x-dashboard-layout>
    <x-slot name="title">Admin — Users</x-slot>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-900 bg-opacity-30 rounded-lg border border-green-500">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <h3 class="text-xl font-bold mb-3">All Users</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ ucfirst($u->role ?? 'user') }}</td>
                            <td>{{ $u->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $u->id) }}" class="btn btn-primary btn-sm">View</a>
                                <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-secondary btn-sm" onclick="return confirm('Delete this user?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-dashboard-layout>
