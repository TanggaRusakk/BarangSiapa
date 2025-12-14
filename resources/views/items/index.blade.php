@extends('layouts.mainlayout')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="fw-bold mb-3" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">All Items</h1>
            <p class="text-secondary">Browse and search through our marketplace</p>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form action="{{ route('items.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search items..." value="{{ request('search') }}">
                <button class="btn" type="submit" style="background: #6A38C2; color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </form>
        </div>
        <div class="col-md-4">
            <!-- Buy/Rent Filter Toggle -->
            <div class="btn-group w-100" role="group">
                <input type="radio" class="btn-check" name="item_type" id="filter-all" value="all" checked>
                <label class="btn btn-outline-primary" for="filter-all">All</label>
                
                <input type="radio" class="btn-check" name="item_type" id="filter-buy" value="buy">
                <label class="btn btn-outline-primary" for="filter-buy">Buy</label>
                
                <input type="radio" class="btn-check" name="item_type" id="filter-rent" value="rent">
                <label class="btn btn-outline-primary" for="filter-rent">Rent</label>
            </div>
        </div>
    </div>

    <!-- Items Grid (3x3) -->
    <div class="row g-4" id="itemsGrid">
        @forelse($items as $item)
            @php
                $isRent = ($item->item_type === 'sewa' || $item->item_type === 'rent');
                $itemTypeClass = $isRent ? 'rent' : 'buy';
            @endphp
            <div class="col-md-4 item-card" data-type="{{ $itemTypeClass }}">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        <span class="badge position-absolute top-0 start-0 m-2" style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }};">
                            {{ $isRent ? 'For Rent' : 'For Sale' }}
                        </span>
                                          <img src="{{ $item->first_image_url ?? (file_exists(public_path('images/items/item_placeholder.jpg')) ? asset('images/items/item_placeholder.jpg') : asset('images/items/item_placeholder.png')) }}" 
                                      class="card-img-top" 
                                      alt="{{ $item->item_name }}"
                                      style="height: 200px; object-fit: cover;">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $item->item_name }}</h5>
                        <p class="card-text text-secondary small">
                            {{ \Illuminate\Support\Str::limit($item->item_description ?? 'No description available', 80) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="h5 mb-0 fw-bold" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                    Rp {{ number_format($item->item_price, 0, ',', '.') }}
                                </span>
                                @if($isRent)
                                    <small class="text-secondary">/{{ $item->rental_duration_unit ?? 'day' }}</small>
                                @endif
                            </div>
                            <a href="{{ route('items.show', $item->id) }}" 
                               class="btn btn-sm" 
                               style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }}; color: white;">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mb-3">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <p class="mb-0">No items found. Try adjusting your filters.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row mt-5">
        <div class="col-12">
                <nav class="items-pagination">{{ $items->links() }}</nav>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('input[name="item_type"]');
    const itemCards = document.querySelectorAll('.item-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('change', function() {
            const filterValue = this.value;
            
            itemCards.forEach(card => {
                const cardType = card.dataset.type;
                
                if (filterValue === 'all') {
                    card.style.display = 'block';
                } else if (filterValue === cardType) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush
