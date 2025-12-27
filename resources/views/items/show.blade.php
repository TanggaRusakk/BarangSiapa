@extends('layouts.mainlayout')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Item Images Gallery -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                 <img src="{{ $item->first_image_url }}" 
                     class="card-img-top" 
                     alt="{{ $item->item_name }}"
                     id="mainImage"
                     style="height: 400px; object-fit: cover;">
            </div>
            
            @if($item->galleries && $item->galleries->count() > 0)
            <div class="d-flex gap-2 flex-wrap">
                 @foreach($item->galleries as $gallery)
                 <img src="{{ $gallery->url }}" 
                     class="img-thumbnail gallery-thumb" 
                     alt="Gallery Image"
                     style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                     onclick="changeMainImage(this.src)">
                @endforeach
            </div>
            @endif
        </div>

        <!-- Item Details -->
        <div class="col-md-6">
            @php
                $isRent = ($item->item_type === 'sewa' || $item->item_type === 'rent');
            @endphp
            
            <span class="badge mb-2" style="background: {{ $isRent ? '#4ADFFF' : '#6A38C2' }}; font-size: 14px;">
                {{ $isRent ? 'For Rent' : 'For Sale' }}
            </span>
            
            <h1 class="fw-bold mb-3">{{ $item->item_name }}</h1>
            
            <div class="mb-3">
                <span class="h2 fw-bold" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    Rp {{ number_format($item->item_price, 0, ',', '.') }}
                </span>
                @if($isRent)
                    <span class="text-secondary">/{{ $item->rental_duration_unit ?? 'day' }}</span>
                @endif
            </div>

            <!-- Sold Count -->
            <div class="mb-3 text-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-inline-block">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span class="ms-2">{{ $item->orderItems->sum('quantity') ?? 0 }} Sold</span>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <h5 class="fw-bold mb-2">Description</h5>
                <p class="text-secondary">{{ $item->item_description ?? 'No description available.' }}</p>
            </div>

            <!-- Stock -->
            <div class="mb-4">
                <h5 class="fw-bold mb-2">Availability</h5>
                <p class="text-secondary">
                    @if($item->item_qty > 0)
                        <span class="badge bg-success">In Stock ({{ $item->item_qty }} available)</span>
                    @else
                        <span class="badge bg-danger">Out of Stock</span>
                    @endif
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 mb-4">
                @auth
                    @if($item->item_qty > 0)
                        <button class="btn btn-lg flex-grow-1" style="background: #6A38C2; color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-inline-block me-2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            {{ $isRent ? 'Rent Now' : 'Add to Cart' }}
                        </button>
                    @else
                        <button class="btn btn-lg flex-grow-1 btn-secondary" disabled>
                            Out of Stock
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-lg flex-grow-1" style="background: #6A38C2; color: white;">
                        Login to {{ $isRent ? 'Rent' : 'Buy' }}
                    </a>
                @endauth
            </div>

            <!-- Vendor Card removed from here and will be shown full-width below -->
        </div>
    </div>

    <!-- Vendor Section (full width) -->
    @if($item->vendor)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Vendor Information</h5>
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $item->vendor->logo_url }}" alt="{{ $item->vendor->vendor_name }}" class="rounded me-3" style="width:64px;height:64px;object-fit:cover;">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $item->vendor->vendor_name }}</h6>
                            <small class="text-secondary">{{ $item->vendor->vendor_address ?? 'No address provided' }}</small>
                        </div>
                    </div>

                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="fw-bold">{{ $item->vendor->items->count() }}</div>
                            <small class="text-secondary">Products</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold">{{ $item->vendor->items->sum(function($i) { return $i->orderItems->sum('quantity'); }) }}</div>
                            <small class="text-secondary">Sold</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold">
                                @php
                                    $avgRating = $item->vendor->items->flatMap->reviews->avg('rating') ?? 0;
                                @endphp
                                {{ number_format($avgRating, 1) }}⭐
                            </div>
                            <small class="text-secondary">Rating</small>
                        </div>
                    </div>

                    @auth
                        <a href="{{ url('/messages?vendor=' . $item->vendor->id) }}" class="btn btn-outline-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-inline-block me-2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            Message Vendor
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Login to Message</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">Customer Reviews</h3>
            
            @if($item->reviews && $item->reviews->count() > 0)
                @foreach($item->reviews as $review)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $review->user->name ?? 'Anonymous' }}</h6>
                                <small class="text-secondary">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <span style="color: #FFD700;">⭐</span>
                                    @else
                                        <span style="color: #ddd;">⭐</span>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="mb-0">{{ $review->comment }}</p>
                    </div>
                </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    No reviews yet. Be the first to review this item!
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
}
</script>
@endpush
