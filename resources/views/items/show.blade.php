@extends('layouts.mainlayout')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-5">
    <!-- Breadcrumb -->
    <div class="mb-4 text-sm text-secondary">
        <a href="{{ url('/') }}" class="hover:text-primary" style="color: #6A38C2;">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('items.index') }}" class="hover:text-primary" style="color: #6A38C2;">Items</a>
        <span class="mx-2">/</span>
        <span class="fw-bold">{{ $item->item_name }}</span>
    </div>

    <div class="row g-4">
        <!-- Left: Images Gallery -->
        <div class="col-md-6">
            @if($item->itemGalleries && $item->itemGalleries->count() > 0)
                <!-- Carousel for multiple images -->
                <div id="itemGalleryCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                    <!-- Main Images -->
                    <div class="carousel-inner rounded shadow-sm" style="max-height: 500px;">
                        @foreach($item->itemGalleries as $index => $gallery)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ $gallery->image_url }}" 
                                     class="d-block w-100" 
                                     alt="{{ $item->item_name }}" 
                                     style="height: 500px; object-fit: cover;">
                                <div class="position-absolute bottom-0 end-0 m-3">
                                    <span class="badge bg-dark bg-opacity-75">{{ $index + 1 }} / {{ $item->itemGalleries->count() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Carousel Controls -->
                    @if($item->itemGalleries->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#itemGalleryCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#itemGalleryCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
                
                <!-- Thumbnail Navigation -->
                @if($item->itemGalleries->count() > 1)
                    <div class="d-flex gap-2 overflow-auto pb-2" style="max-width: 100%;">
                        @foreach($item->itemGalleries as $index => $gallery)
                            <img src="{{ $gallery->image_url }}" 
                                 alt="{{ $item->item_name }}" 
                                 class="rounded thumbnail-img {{ $index === 0 ? 'active-thumbnail' : '' }}" 
                                 data-bs-target="#itemGalleryCarousel" 
                                 data-bs-slide-to="{{ $index }}"
                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 3px solid transparent; transition: all 0.3s; flex-shrink: 0;"
                                 onclick="setActiveThumbnail(this)">
                        @endforeach
                    </div>
                @endif
            @else
                <!-- Fallback single image -->
                <img src="{{ $item->first_image_url }}" 
                     alt="{{ $item->item_name }}" 
                     class="img-fluid rounded shadow-sm" 
                     style="width: 100%; max-height: 500px; object-fit: cover;">
            @endif
        </div>

        <!-- Right: Details -->
        <div class="col-md-6">
            @php
                $isRent = ($item->item_type === 'rent');
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
                    <span class="text-secondary">/ {{ $item->rental_duration_unit ?? 'day' }}</span>
                @endif
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
                    @if(($item->item_stock ?? 0) > 0)
                        <span class="badge bg-success">In Stock ({{ $item->item_stock }} available)</span>
                    @else
                        <span class="badge bg-danger">Out of Stock</span>
                    @endif
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 mb-4">
                @auth
                    @php $userRole = auth()->user()->role ?? 'user'; @endphp
                    @if(in_array($userRole, ['admin', 'vendor']))
                        <div class="alert alert-info flex-grow-1 mb-0" style="background: rgba(106,56,194,0.2); border: 1px solid rgba(106,56,194,0.4); color: var(--soft-lilac);">
                            <small>{{ $userRole === 'admin' ? 'Admin' : 'Vendor' }} tidak dapat membuat pesanan.</small>
                        </div>
                    @elseif($item->item_status === 'available' && ($item->item_stock ?? 0) > 0)
                        <a href="{{ route('checkout', $item->id) }}" class="btn btn-lg flex-grow-1" style="background: #6A38C2; color: white;">
                            {{ $isRent ? 'Rent Now' : 'Buy Now' }}
                        </a>
                    @else
                        <button class="btn btn-lg flex-grow-1 btn-secondary" disabled>Out of Stock</button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-lg flex-grow-1" style="background: #6A38C2; color: white;">
                        Login to {{ $isRent ? 'Rent' : 'Buy' }}
                    </a>
                @endauth
            </div>
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
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">{{ $item->vendor->vendor_name }}</h6>
                            <p class="text-secondary mb-0 small">{{ $item->vendor->location ?? 'Location not set' }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="text-center p-2 rounded" style="background: rgba(106,56,194,0.1);">
                                <div class="h5 fw-bold mb-0" style="color: #6A38C2;">{{ $item->vendor->items->count() ?? 0 }}</div>
                                <small class="text-secondary">Products</small>
                            </div>
                            <div class="text-center p-2 rounded" style="background: rgba(106,56,194,0.1);">
                                <div class="h5 fw-bold mb-0" style="color: #6A38C2;">4.5</div>
                                <small class="text-secondary">Rating</small>
                            </div>
                        </div>
                    </div>

                    @auth
                        <a href="{{ url('/messages?vendor=' . $item->vendor->id) }}" class="btn btn-outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-inline-block me-2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            Message Vendor
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">Login to Message</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Reviews Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Customer Reviews</h5>
                    
                    @if($item->reviews && $item->reviews->count() > 0)
                        @foreach($item->reviews as $review)
                        <div class="d-flex gap-3 mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); color: white; font-weight: bold;">
                                    {{ substr($review->user->name ?? 'U', 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $review->user->name ?? 'Anonymous' }}</h6>
                                        <div class="d-flex gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span style="color: {{ $i <= ($review->rating ?? 0) ? '#FFD700' : '#ddd' }};">★</span>
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-secondary">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 text-secondary">{{ $review->comment }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <p class="text-secondary mb-0">No reviews yet. Be the first to review this item!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Thumbnail active state management
function setActiveThumbnail(element) {
    // Remove active class from all thumbnails
    document.querySelectorAll('.thumbnail-img').forEach(img => {
        img.classList.remove('active-thumbnail');
        img.style.borderColor = 'transparent';
    });
    
    // Add active class to clicked thumbnail
    element.classList.add('active-thumbnail');
    element.style.borderColor = '#6A38C2';
}

// Update active thumbnail when carousel slides
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('itemGalleryCarousel');
    if (carousel) {
        carousel.addEventListener('slide.bs.carousel', function(event) {
            const thumbnails = document.querySelectorAll('.thumbnail-img');
            thumbnails.forEach((thumb, index) => {
                if (index === event.to) {
                    thumb.classList.add('active-thumbnail');
                    thumb.style.borderColor = '#6A38C2';
                } else {
                    thumb.classList.remove('active-thumbnail');
                    thumb.style.borderColor = 'transparent';
                }
            });
        });
    }
});

// Add hover effect to thumbnails
document.querySelectorAll('.thumbnail-img').forEach(img => {
    img.addEventListener('mouseover', function() {
        if (!this.classList.contains('active-thumbnail')) {
            this.style.borderColor = '#FF3CAC';
        }
    });
    
    img.addEventListener('mouseout', function() {
        if (!this.classList.contains('active-thumbnail')) {
            this.style.borderColor = 'transparent';
        }
    });
});
</script>

<style>
.active-thumbnail {
    border-color: #6A38C2 !important;
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(106, 56, 194, 0.3);
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(106, 56, 194, 0.8);
    border-radius: 50%;
    padding: 20px;
}

.carousel-control-prev:hover .carousel-control-prev-icon,
.carousel-control-next:hover .carousel-control-next-icon {
    background-color: rgba(106, 56, 194, 1);
}
</style>
@endpush
