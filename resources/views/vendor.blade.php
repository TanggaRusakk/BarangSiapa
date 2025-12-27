@extends('layouts.mainlayout')

@section('content')

<section class="section">
    <div class="container">
        <div class="card">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    @if($vendor && $vendor->logo_url)
                        <img src="{{ $vendor->logo_url }}" 
                             alt="{{ $vendor->vendor_name }}" 
                             class="w-32 h-32 rounded-lg object-cover">
                    @else
                        <div class="w-32 h-32 rounded-lg bg-royal-purple flex items-center justify-center text-neon-pink font-bold">{{ substr($vendor->vendor_name,0,1) }}</div>
                    @endif
                </div>

                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-white mb-2">{{ $vendor->vendor_name }}</h1>
                    <p class="text-secondary mb-2">📍 {{ $vendor->location ?? 'Not specified' }}</p>
                    <p class="text-secondary mb-4">{{ $vendor->description ?? '' }}</p>

                    <div class="flex gap-4 flex-wrap">
                        <div class="stat-card">
                            <div class="stat-label">Total Products</div>
                            <div class="stat-value">{{ $vendor->items()->count() }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Member Since</div>
                            <div class="stat-value">{{ $vendor->created_at->format('Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <form action="{{ route('vendors.show', $vendor) }}" method="GET" class="search-container">
                    <input type="text" name="search" class="search-input" placeholder="Search vendor products..." value="{{ request('search') }}">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <button class="search-btn" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <div>
                <form action="{{ route('vendors.show', $vendor) }}" method="GET" id="sortForm">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <select name="sort" class="form-input" onchange="document.getElementById('sortForm').submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A-Z</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="category-filter mb-6">
            <a href="{{ route('vendors.show', $vendor) }}?search={{ request('search') }}&sort={{ request('sort') }}" class="category-chip {{ !request('category') ? 'active' : '' }}">All Categories</a>
            @foreach($categories as $cat)
                <a href="{{ route('vendors.show', $vendor) }}?category={{ $cat->id }}&search={{ request('search') }}&sort={{ request('sort') }}" class="category-chip {{ request('category') == $cat->id ? 'active' : '' }}">{{ $cat->category_name }}</a>
            @endforeach
        </div>

        <h2 class="section-title text-white mb-6">Products from {{ $vendor->vendor_name }}</h2>

        @if($items->count() > 0)
            <div class="product-grid">
                @foreach($items as $item)
                    @php $isRent = ($item->item_type === 'sewa' || $item->item_type === 'rent'); @endphp
                    <div class="product-card">
                        <div class="product-badge {{ $isRent ? 'badge-rent' : 'badge-buy' }}">{{ $isRent ? 'For Rent' : 'Buy Now' }}</div>
                        <img src="{{ $item->first_image_url }}" alt="{{ $item->item_name }}" class="product-image">
                        <div class="p-4">
                            <h3 class="text-lg font-bold mb-2">{{ $item->item_name }}</h3>
                            <p class="text-sm text-secondary mb-3">{{ \Illuminate\Support\Str::limit($item->item_description ?? '', 80) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold text-gradient">@if($isRent) Rp{{ number_format($item->item_price) }} / {{ $item->rental_duration_unit ?? 'day' }} @else Rp{{ number_format($item->item_price) }} @endif</span>
                                <a href="{{ route('items.show', $item->id) }}" class="btn {{ $isRent ? 'btn-accent' : 'btn-primary' }} btn-sm">{{ $isRent ? 'Rent Now' : 'View Details' }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $items->links() }}</div>
        @else
            <div class="card text-center py-12">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-2xl font-bold mb-2">No Products Found</h3>
                <p class="text-secondary">Try adjusting your search or filter criteria</p>
            </div>
        @endif
    </div>
</section>

@endsection
