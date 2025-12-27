@extends('layouts.mainlayout')

@section('content')

<div class="container-fluid py-5">
    <div class="mb-4">
        <h1 class="display-5 fw-bold text-gradient mb-1">Browse Items</h1>
        <p class="text-secondary">Discover amazing products from our vendors</p>
    </div>

    <div class="mb-4 overflow-auto hide-scrollbar">
        <div class="d-flex gap-2">
            <a href="{{ route('items.index') }}" class="category-btn {{ !request('category') ? 'active' : '' }}">All Categories</a>
            @foreach($categories as $cat)
                <a href="{{ route('items.index', ['category' => $cat->id, 'search' => request('search')]) }}" class="category-btn {{ request('category') == $cat->id ? 'active' : '' }}">{{ $cat->category_name }}</a>
            @endforeach
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <p class="text-soft-lilac mb-0">
            Showing {{ $items->count() }} of {{ $items->total() }} items
            @if(request('search')) for "<span class="text-neon-pink">{{ request('search') }}</span>" @endif
            @if(request('category')) @php $selectedCat = $categories->firstWhere('id', request('category')); @endphp in <span class="text-neon-pink">{{ $selectedCat->category_name ?? 'Unknown' }}</span> @endif
        </p>
    </div>

    @if($items->count())
        <div class="row g-4">
            @foreach($items as $item)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('items.show', $item->id) }}" class="card h-100 text-decoration-none text-reset hover-border-neon-pink">
                        <div class="position-relative" style="height:260px;overflow:hidden;">
                            <img src="{{ $item->first_image_url }}" alt="{{ $item->item_name }}" class="w-100 h-100 object-fit-cover">

                            <span class="badge position-absolute top-2 end-2 {{ $item->item_status === 'available' ? 'bg-success' : 'bg-secondary' }} text-white">{{ ucfirst($item->item_status ?? 'available') }}</span>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title text-white mb-2 text-truncate">{{ $item->item_name }}</h5>
                            <p class="card-text text-secondary small mb-3 text-truncate">{{ $item->item_description ?? 'No description available' }}</p>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <div class="fw-bold text-gradient">Rp {{ number_format($item->item_price ?? 0, 0, ',', '.') }}</div>
                                    <div class="small text-secondary">/{{ $item->item_type === 'rent' ? 'day' : 'item' }}</div>
                                </div>
                                <div>
                                    <span class="badge rounded-pill bg-royal-purple text-neon-pink">{{ ucfirst($item->item_type ?? 'sale') }}</span>
                                </div>
                            </div>

                            <div class="mt-2 small d-flex align-items-center gap-2 text-secondary">
                                @php
                                    $vendor = $item->vendor ?? null;
                                    $vendorLogo = $vendor && $vendor->logo_url ? $vendor->logo_url : asset('images/vendor/vendor_placeholder.jpg');
                                @endphp
                                <img src="{{ $vendorLogo }}" alt="{{ $vendor->vendor_name ?? 'Vendor' }}" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;">
                                <a href="{{ $vendor ? route('vendors.show', $vendor) : '#' }}" class="text-secondary text-decoration-none">{{ $vendor->vendor_name ?? 'Unknown Vendor' }}</a>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    @else
        <div class="card text-center py-5">
            <h3 class="text-white">No Items Found</h3>
            <p class="text-soft-lilac">Try adjusting your search or filter.</p>
            <a href="{{ route('items.index') }}" class="btn btn-primary">View All Items</a>
        </div>
    @endif
</div>

@push('styles')
<style>
    .category-btn {
        padding: 0.45rem 1rem;
        background: rgba(9, 9, 15, 0.6);
        border: 1px solid rgba(106, 56, 194, 0.4);
        border-radius: 0.5rem;
        color: #C4B5FD;
        transition: all 0.25s;
        text-decoration: none;
        display: inline-block;
    }
    .category-btn.active {
        background: rgba(106, 56, 194, 0.2);
        border-color: #FF3CAC;
        color: #FF3CAC;
    }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .hover-border-neon-pink { transition: border-color .2s, box-shadow .2s; }
    .hover-border-neon-pink:hover { box-shadow: 0 6px 20px rgba(255,60,172,0.08); border-color: rgba(255,60,172,0.25); }
    .object-fit-cover { object-fit: cover; }
</style>
@endpush

@endsection
