<x-dashboard-layout>
    <x-slot name="title">Admin — Categories</x-slot>

    @if(session('success'))
        <div id="persistent-success" class="persistent-toast">
            <div class="toast-content">{{ session('success') }}</div>
            <button id="persistent-success-close" class="toast-close">✕</button>
        </div>
    @endif

    <div class="mb-4 admin-categories-header">
        <h3 class="h5 mb-0">Categories</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="create-form">
            @csrf
            <input type="text" name="category_name" class="form-control form-control-sm create-input" placeholder="New category name" required>
            <button class="btn btn-sm btn-primary create-btn">Create</button>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                            <tr>
                                <td>{{ $cat->category_name }}</td>
                                <td>{{ $cat->created_at ? $cat->created_at->format('Y-m-d') : '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-secondary" onclick="return confirm('Delete this category?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-dashboard-layout>

@push('scripts')
<script>
    (function(){
        const btn = document.getElementById('persistent-success-close');
        if(!btn) return;
        btn.addEventListener('click', function(){
            const t = document.getElementById('persistent-success');
            if(t) t.remove();
        });
    })();
</script>
@endpush
