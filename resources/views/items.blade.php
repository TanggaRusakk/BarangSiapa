@extends('layouts.mainlayout')

@section('content')

<div class="container py-5">
    <div class="mb-4">
        <h1 class="display-5 fw-bold mb-1" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Browse Items</h1>
        <p class="text-secondary">Discover amazing products from our vendors</p>
    </div>

    <!-- Category Filter -->
    <div class="mb-4 overflow-auto" style="white-space: nowrap;">
        <div class="d-inline-flex gap-2">
            <a href="{{ route('items.index') }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-secondary' }}" style="{{ !request('category') ? 'background: #6A38C2; border-color: #6A38C2;' : '' }}">All Categories</a>
            @foreach($categories as $cat)
                <a href="{{ route('items.index', ['category' => $cat->id, 'search' => request('search')]) }}" class="btn {{ request('category') == $cat->id ? 'btn-primary' : 'btn-outline-secondary' }}" style="{{ request('category') == $cat->id ? 'background: #6A38C2; border-color: #6A38C2;' : '' }}">{{ $cat->category_name }}</a>
            @endforeach
        </div>
    </div>

    <!-- Results Count -->
    <div class="mb-3">
        <p class="text-secondary mb-0">
            Showing {{ $items->count() }} of {{ $items->total() }} items
            @if(request('search')) for "<span style="color: #FF3CAC;">{{ request('search') }}</span>" @endif
            @if(request('category')) 
                @php $selectedCat = $categories->firstWhere('id', request('category')); @endphp 
                in <span style="color: #FF3CAC;">{{ $selectedCat->category_name ?? 'Unknown' }}</span> 
            @endif
        </p>
    </div>

    @if($items->count())
        <div class="row g-4">
            @foreach($items as $item)
                @php
                    $isRent = ($item->item_type === 'rent');
                    $vendor = $item->vendor ?? null;
                @endphp
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <!-- Item Image -->
                        <div class="position-relative">
                            <a href="{{ route('items.show', $item->id) }}">
                                <img src="{{ $item->first_image_url }}" alt="{{ $item->item_name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            </a>
                            <span class="badge position-absolute top-0 end-0 m-2 {{ $item->item_status === 'available' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($item->item_status ?? 'available') }}
                            </span>
                            <span class="badge position-absolute top-0 start-0 m-2" style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }}; color: {{ $isRent ? '#000' : '#fff' }};">
                                {{ $isRent ? 'RENT' : 'BUY' }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title fw-bold mb-1 text-truncate">
                                <a href="{{ route('items.show', $item->id) }}" class="text-decoration-none text-dark">{{ $item->item_name }}</a>
                            </h6>
                            <p class="card-text text-secondary small mb-2 text-truncate">{{ $item->item_description ?? 'No description available' }}</p>

                            <div class="mt-auto">
                                <!-- Price -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="fw-bold" style="color: #FF3CAC;">Rp {{ number_format($item->item_price ?? 0, 0, ',', '.') }}</span>
                                        <small class="text-secondary d-block">/{{ $isRent ? ($item->rental_duration_unit ?? 'day') : 'item' }}</small>
                                    </div>
                                </div>

                                <!-- Vendor Info -->
                                @if($vendor)
                                <div class="d-flex align-items-center gap-2 p-2 rounded" style="background: rgba(0,0,0,0.05);">
                                    <img src="{{ $vendor->logo_url }}" alt="{{ $vendor->vendor_name }}" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                                    <a href="{{ route('vendors.show', $vendor) }}" class="text-decoration-none text-secondary small text-truncate">{{ $vendor->vendor_name }}</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    @else
        <div class="card shadow-sm text-center py-5">
            <div class="card-body">
                <div class="mb-3" style="font-size: 48px;">📦</div>
                <h3 class="fw-bold">No Items Found</h3>
                <p class="text-secondary">Try adjusting your search or filter.</p>
                <a href="{{ route('items.index') }}" class="btn" style="background: #6A38C2; color: white;">View All Items</a>
            </div>
        </div>
    @endif
</div>

@endsection
