@extends('layouts.mainlayout')

@section('content')

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="toast-success" class="position-fixed top-0 end-0 mt-4 me-3 text-white px-3 py-2 rounded shadow d-flex align-items-center gap-2" style="z-index: 9999; background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%);">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('toast-success').remove()" class="btn-close btn-close-white ms-3" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div id="toast-error" class="position-fixed top-0 end-0 mt-4 me-3 text-white px-3 py-2 rounded shadow d-flex align-items-center gap-2" style="z-index: 9999; background: #FF3CAC;">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('error') }}</span>
            <button onclick="document.getElementById('toast-error').remove()" class="btn-close btn-close-white ms-3" aria-label="Close"></button>
        </div>
    @endif

    <div class="container py-5">
        <!-- Breadcrumb -->
        <div class="mb-4 text-sm text-secondary">
            <a href="{{ url('/') }}" class="text-decoration-none" style="color: #6A38C2;">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('items.index') }}" class="text-decoration-none" style="color: #6A38C2;">Items</a>
            <span class="mx-2">/</span>
            <span class="fw-bold">{{ $item->item_name }}</span>
        </div>

        @php
            $isRent = ($item->item_type === 'rent');
        @endphp

        <!-- Main Card with Purple Glow -->
        <div class="card shadow-lg" style="border: 2px solid rgba(106, 56, 194, 0.3); box-shadow: 0 0 30px rgba(106, 56, 194, 0.2), 0 0 60px rgba(255, 60, 172, 0.1);">
            <div class="card-body p-4">
                
                <!-- Image Gallery -->
                <div class="mb-4">
                    @if($item->galleries && $item->galleries->count() > 0)
                        <img id="mainImage" src="{{ $item->galleries->first()->image_url ?? $item->first_image_url }}" alt="{{ $item->item_name }}" class="w-100 rounded mb-3" style="max-height: 500px; object-fit: cover;">
                        
                        @if($item->galleries->count() > 1)
                            <div class="d-flex gap-2 overflow-auto pb-2">
                                @foreach($item->galleries as $gallery)
                                    <img src="{{ $gallery->image_url }}" alt="{{ $item->item_name }}" 
                                         class="rounded gallery-thumb" 
                                         style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 3px solid transparent;"
                                         onclick="changeMainImage(this, '{{ $gallery->image_url }}')">
                                @endforeach
                            </div>
                        @endif
                    @else
                        <img src="{{ $item->first_image_url }}" alt="{{ $item->item_name }}" class="w-100 rounded" style="max-height: 500px; object-fit: cover;">
                    @endif
                </div>

                <!-- Item Info Section -->
                <div class="p-4 rounded mb-4" style="background: linear-gradient(135deg, rgba(106, 56, 194, 0.1) 0%, rgba(255, 60, 172, 0.05) 100%);">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge" style="background: {{ $isRent ? '#FF3CAC' : '#6A38C2' }}; color: #fff; font-size: 14px;">
                            {{ $isRent ? 'RENT' : 'BUY' }}
                        </span>
                        <span class="badge" style="background: {{ $item->item_status === 'available' ? '#6A38C2' : '#666' }}; color: #fff;">
                            {{ ucfirst($item->item_status ?? 'available') }}
                        </span>
                    </div>

                    <h1 class="h2 fw-bold mb-3" style="color: #333;">{{ $item->item_name }}</h1>
                    
                    <div class="h3 fw-bold mb-4" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        Rp {{ number_format($item->item_price, 0, ',', '.') }}
                        @if($isRent)
                            <span class="fs-6" style="-webkit-text-fill-color: #666;">/ {{ $item->rental_duration_unit ?? 'day' }}</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-2" style="color: #6A38C2;">Description</h5>
                        <p class="text-secondary mb-0">{{ $item->item_description ?? 'No description available.' }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-2" style="color: #6A38C2;">Stock</h5>
                        @if(($item->item_stock ?? 0) > 0)
                            <span class="badge" style="background: #6A38C2; color: #fff; font-size: 14px;">{{ $item->item_stock }} available</span>
                        @else
                            <span class="badge" style="background: #FF3CAC; color: #fff; font-size: 14px;">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid">
                        @auth
                            @php $userRole = auth()->user()->role ?? 'user'; @endphp
                            @if(in_array($userRole, ['admin', 'vendor']))
                                <div class="alert alert-info mb-0" style="background: rgba(106,56,194,0.2); border: 1px solid rgba(106,56,194,0.4); color: var(--soft-lilac);">
                                    <small>{{ $userRole === 'admin' ? 'Admin' : 'Vendor' }} tidak dapat membuat pesanan.</small>
                                </div>
                            @elseif($item->item_status === 'available' && ($item->item_stock ?? 0) > 0)
                                <a href="{{ route('checkout', $item->id) }}" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); border: none;">
                                    {{ $isRent ? 'Rent Now' : 'Buy Now' }}
                                </a>
                            @else
                                <button class="btn btn-lg" style="background: #ccc; color: #666;" disabled>Out of Stock</button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #6A38C2 0%, #FF3CAC 100%); border: none;">
                                Login to {{ $isRent ? 'Rent' : 'Buy' }}
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Vendor Information Section -->
                @if($item->vendor)
                <div class="p-4 rounded mb-4" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                    <h5 class="fw-bold mb-3" style="color: #FF3CAC;">Vendor Information</h5>
                    
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $item->vendor->logo_url }}" alt="{{ $item->vendor->vendor_name }}" class="rounded me-3" style="width: 64px; height: 64px; object-fit: cover; border: 2px solid #6A38C2;">
                        <div>
                            <h6 class="fw-bold mb-1" style="color: #fff;">{{ $item->vendor->vendor_name }}</h6>
                            <p class="mb-0 small" style="color: #C4B5FD;">{{ $item->vendor->location ?? 'Location not set' }}</p>
                        </div>
                    </div>

                    @if($item->vendor->vendor_description)
                        <p class="small mb-3" style="color: #C4B5FD;">{{ Str::limit($item->vendor->vendor_description, 150) }}</p>
                    @endif

                    <div class="d-flex gap-2 mb-3">
                        <div class="text-center flex-grow-1 p-3 rounded" style="background: rgba(106, 56, 194, 0.3);">
                            <div class="h4 fw-bold mb-0" style="color: #FF3CAC;">{{ $item->vendor->items->count() ?? 0 }}</div>
                            <small style="color: #C4B5FD;">Products</small>
                        </div>
                        <div class="text-center flex-grow-1 p-3 rounded" style="background: rgba(106, 56, 194, 0.3);">
                            <div class="h4 fw-bold mb-0" style="color: #FF3CAC;">4.5</div>
                            <small style="color: #C4B5FD;">Rating</small>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('messages.start', ['vendor_id' => $item->vendor->id, 'item_id' => $item->id]) }}" class="btn w-100" style="background: transparent; border: 2px solid #6A38C2; color: #C4B5FD;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-inline-block me-2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            Message Vendor
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn w-100" style="background: transparent; border: 2px solid #6A38C2; color: #C4B5FD;">Login to Message</a>
                    @endauth
                </div>
                @endif

                <!-- Reviews Section -->
                <div class="p-4 rounded" style="background: rgba(106, 56, 194, 0.05);">
                    <h4 class="fw-bold mb-4" style="color: #6A38C2;">Customer Reviews</h4>
                    @include('partials.item-reviews', ['item' => $item])
                </div>

            </div>
        </div>
    </div>

@endsection

@push('styles')
@endpush

@push('scripts')
<script>
    // Auto-hide toast messages
    setTimeout(() => {
        const successToast = document.getElementById('toast-success');
        const errorToast = document.getElementById('toast-error');
        if (successToast) successToast.remove();
        if (errorToast) errorToast.remove();
    }, 5000);

    // Change main image on thumbnail click
    function changeMainImage(el, src) {
        document.getElementById('mainImage').src = src;
        // Update active state
        document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }
</script>
@endpush
