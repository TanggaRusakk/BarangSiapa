@extends('layouts.mainlayout')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="display-5 fw-bold mb-2" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">All Items</h1>
        <p class="text-secondary">Browse and search through our marketplace</p>
    </div>

    <!-- Search & Filters -->
    <div class="row mb-4">
        <div class="col-12 col-md-8 mb-3 mb-md-0">
            <form action="{{ route('items.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control rounded-start" placeholder="Search items..." value="{{ request('search') }}">
                <button class="btn text-white rounded-end" type="submit" style="background: #6A38C2;"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </form>
        </div>
        <div class="col-12 col-md-4">
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

    <!-- Items Grid -->
    <div class="row g-4" id="itemsGrid">
        @forelse($items as $item)
            @php
                $isRent = ($item->item_type === 'rent');
                $itemTypeClass = $isRent ? 'rent' : 'buy';
            @endphp
            <div class="col-12 col-sm-6 col-lg-4 item-card" data-type="{{ $itemTypeClass }}">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        <span class="badge position-absolute top-0 start-0 m-2" style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }}; color: {{ $isRent ? '#000' : '#fff' }};">{{ $isRent ? 'RENT' : 'BUY' }}</span>
                        <img src="{{ $item->first_image_url }}" class="card-img-top" alt="{{ $item->item_name }}" style="height: 200px; object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold mb-1">{{ $item->item_name }}</h5>
                        <p class="card-text text-secondary small mb-3">{{ \Illuminate\Support\Str::limit($item->item_description ?? 'No description available', 80) }}</p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="fw-bold fs-5" style="color: #FF3CAC;">Rp {{ number_format($item->item_price, 0, ',', '.') }}</span>
                                    @if($isRent)
                                        <small class="text-secondary">/{{ $item->rental_duration_unit ?? 'item' }}</small>
                                    @else
                                        <small class="text-secondary">/item</small>
                                    @endif
                                </div>
                                <span class="badge" style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }}; color: {{ $isRent ? '#000' : '#fff' }};">{{ $isRent ? 'RENT' : 'BUY' }}</span>
                            </div>
                            
                            <!-- Vendor Info -->
                            @if($item->vendor)
                            <div class="d-flex align-items-center p-2 rounded mb-3" style="background: rgba(0,0,0,0.7);">
                                <img src="{{ $item->vendor->logo_url }}" alt="{{ $item->vendor->vendor_name }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                <span class="text-white small">{{ $item->vendor->vendor_name }}</span>
                            </div>
                            @endif
                            
                            <a href="{{ route('items.show', $item->id) }}" class="btn w-100 text-white" style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }}; color: {{ $isRent ? '#000' : '#fff' }} !important;">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mb-3 mx-auto d-block">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <p class="mb-0">No items found. Try adjusting your filters.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $items->links() }}
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
