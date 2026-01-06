@includeWhen(true, 'dashboard.layout')

<x-dashboard-layout>
    <x-slot name="title">Admin Dashboard</x-slot>

    @include('dashboard.admin._overview', [
        'totalUsers' => $totalUsers ?? 0,
        'activeVendors' => $activeVendors ?? 0,
        'totalProducts' => $totalProducts ?? 0,
        'totalCategories' => $totalCategories ?? 0,
        'revenueThisMonth' => $revenueThisMonth ?? 0,
    ])

    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Categories</h5>
                    <a href="{{ route('admin.categories') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if(!empty($recentCategories) && $recentCategories->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentCategories->take(6) as $category)
                                <div class="list-group-item border-0 py-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-semibold">{{ $category->category_name }}</h6>
                                        <small class="text-muted">{{ $category->category_slug ?? '' }}</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No categories yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
