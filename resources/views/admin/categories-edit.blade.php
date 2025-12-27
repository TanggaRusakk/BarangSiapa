<x-dashboard-layout>
    <x-slot name="title">Admin — Edit Category</x-slot>

    <div class="mb-4">
        <a href="{{ route('admin.categories') }}" class="btn btn-secondary">← Back to Categories</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="category_name" value="{{ old('category_name', $category->category_name) }}" class="form-control" required>
                    @error('category_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Save changes</button>
                </div>
            </form>

            <div class="mt-3">
                <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('Delete this category?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-secondary">Delete</button>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>
