@extends('layouts.mainlayout')

@section('content')

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="toast-success" class="position-fixed top-0 end-0 mt-4 me-3 bg-success text-white px-3 py-2 rounded shadow d-flex align-items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('toast-success').remove()" class="btn-close btn-close-white ms-3" aria-label="Close"></button>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div id="toast-error" class="position-fixed top-0 end-0 mt-4 me-3 bg-danger text-white px-3 py-2 rounded shadow d-flex align-items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('error') }}</span>
            <button onclick="document.getElementById('toast-error').remove()" class="btn-close btn-close-white ms-3" aria-label="Close"></button>
        </div>
    @endif

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-5 item-detail">
        <!-- Breadcrumb -->
        <div class="mb-6 text-sm text-secondary">
            <a href="{{ url('/') }}" class="hover:text-neon-pink">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('items.index') }}" class="hover:text-neon-pink">Items</a>
            <span class="mx-2">/</span>
            <span class="text-white">{{ $item->item_name }}</span>
        </div>

        <div class="flex flex-wrap -mx-2">
            <!-- Left: Images & Main Info -->
            <div class="w-full lg:w-8/12 px-2">
                <!-- Image Gallery -->
                <div class="mb-6">
                    @if($item->galleries->count() > 0)
                        <div class="mb-4">
                            <img id="main-image" src="{{ $item->first_image_url }}" 
                                   alt="{{ $item->item_name }}"
                                   class="w-full rounded" style="height:24rem; object-fit:cover;">
                        </div>
                        
                        @if($item->galleries->count() > 1)
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($item->galleries as $gallery)
                                    <div>
                                        <img src="{{ $gallery->url }}" 
                                             alt="Gallery"
                                             class="w-full rounded cursor-pointer"
                                             style="height:6rem; object-fit:cover;"
                                             onclick="document.getElementById('main-image').src=this.src">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @else
                        <div style="height:24rem; background:var(--midnight-black); border-radius:.5rem; display:flex; align-items:center; justify-content:center;" class="w-100 main-placeholder">
                            <svg class="w-16 h-16 text-soft-lilac" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Item Details -->
                <div>
                    <h1 class="text-3xl font-bold text-gradient mb-4">{{ $item->item_name }}</h1>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="text-3xl font-bold text-gradient">
                            Rp {{ number_format($item->item_price, 0, ',', '.') }}
                        </div>
                        <span class="px-3 py-1 rounded-full bg-royal-purple bg-opacity-20 text-neon-pink border border-neon-pink border-opacity-30">
                            {{ ucfirst($item->item_type ?? 'sale') }}
                        </span>
                        <span class="px-3 py-1 rounded-full {{ $item->item_status === 'available' ? 'bg-green-500' : 'bg-gray-500' }} text-white">
                            {{ ucfirst($item->item_status ?? 'available') }}
                        </span>
                    </div>

                    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 item-detail">
                        <!-- Breadcrumb -->
                        <div class="mb-4 text-sm text-secondary">
                            <a href="{{ url('/') }}" class="hover:text-neon-pink">Home</a>
                            <span class="mx-2">/</span>
                            <a href="{{ route('items.index') }}" class="hover:text-neon-pink">Items</a>
                            <span class="mx-2">/</span>
                            <span class="text-white">{{ $item->item_name }}</span>
                        </div>

                        <!-- Top row: Image | Details -->
                        <div class="flex flex-wrap -mx-2">
                            <!-- Image column -->
                            <div class="w-full lg:w-1/2 px-2">
                                <div>
                                    @if($item->galleries->count() > 0)
                                        <div class="mb-4">
                                            <img id="main-image" src="{{ $item->first_image_url }}" alt="{{ $item->item_name }}" class="img-fluid rounded" style="height:24rem; object-fit:cover;">
                                        </div>

                                        @if($item->galleries->count() > 1)
                                            <div class="grid grid-cols-4 gap-2">
                                                @foreach($item->galleries as $gallery)
                                                    <div>
                                                        <img src="{{ $gallery->url }}" alt="Gallery" class="w-full rounded cursor-pointer" style="height:6rem; object-fit:cover;" onclick="document.getElementById('main-image').src=this.src">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        @else
                                        <div style="height:24rem; background:var(--midnight-black); border-radius:.5rem; display:flex; align-items:center; justify-content:center;" class="w-100 main-placeholder">
                                            <svg class="w-16 h-16 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Details column -->
                            <div class="w-full lg:w-1/2 px-2">
                                <div class="p-6">
                                    <h1 class="text-2xl lg:text-3xl font-bold text-gradient mb-3">{{ $item->item_name }}</h1>

                                    <div class="mb-4">
                                        <div class="text-2xl font-bold text-gradient">Rp {{ number_format($item->item_price, 0, ',', '.') }}</div>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="px-3 py-1 rounded-full bg-royal-purple bg-opacity-20 text-neon-pink border border-neon-pink border-opacity-30">{{ ucfirst($item->item_type ?? 'sale') }}</span>
                                            <span class="px-3 py-1 rounded-full {{ $item->item_status === 'available' ? 'bg-green-500' : 'bg-gray-500' }} text-white">{{ ucfirst($item->item_status ?? 'available') }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h3 class="text-lg font-semibold text-white mb-2">Description</h3>
                                        <p class="text-secondary leading-relaxed">{{ $item->item_description ?? 'No description available for this item.' }}</p>
                                    </div>

                                    @if($item->item_type === 'rent')
                                        <div class="bg-royal-purple bg-opacity-10 border border-royal-purple border-opacity-30 rounded-lg p-3 mb-4">
                                            <h4 class="font-semibold text-white mb-1">Rental Information</h4>
                                            <p class="text-secondary text-sm">Rp {{ number_format($item->item_price, 0, ',', '.') }} per day</p>
                                        </div>
                                    @endif

                                    <div class="flex gap-3">
                                        @auth
                                            @if($item->item_status === 'available' && $item->item_stock > 0)
                                                <a href="{{ route('checkout', $item->id) }}" class="inline-block w-full text-center px-4 py-2 rounded text-white" style="background: #6A38C2;">
                                                    {{ $item->item_type === 'rent' ? 'Rent Now' : 'Order Now' }}
                                                </a>
                                            @else
                                                        <button class="inline-block w-full text-center px-4 py-2 rounded bg-gray-400 text-white" disabled>Out of Stock</button>
                                            @endif
                                            <button class="btn bg-midnight-black border-2 border-royal-purple hover:border-neon-pink">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="inline-block w-full text-center px-4 py-2 rounded text-white" style="background: #6A38C2;">Login to {{ $item->item_type === 'rent' ? 'Rent' : 'Purchase' }}</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vendor info (full width) -->
                            <div class="mt-6">
                            <div class="p-4">
                                        <h3 class="text-lg font-bold text-white mb-3">Sold By</h3>
                                @if($item->vendor)
                                    <div class="flex items-center gap-4">
                                        <div class="w-20 h-20 rounded-lg overflow-hidden">
                                            @if($item->vendor && $item->vendor->logo_url)
                                                <img src="{{ $item->vendor->logo_url }}" alt="{{ $item->vendor->vendor_name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-royal-purple flex items-center justify-center text-neon-pink font-bold">{{ substr($item->vendor->vendor_name,0,1) }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-white">{{ $item->vendor->vendor_name }}</h4>
                                            <p class="text-sm text-secondary">{{ $item->vendor->location ?? 'Not specified' }}</p>
                                        </div>
                                        <div class="ml-auto">
                                            <a href="{{ route('vendors.show', $item->vendor) }}" class="inline-block px-3 py-1 rounded text-white" style="background: #6A38C2;">View All Products</a>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-secondary">Vendor information not available.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Reviews (full width) -->
                        <div class="mt-6">
                            <div class="card p-4">
                                <h2 class="text-2xl font-bold text-white mb-4">Customer Reviews</h2>
                                <!-- Reuse existing review form and list markup -->
                                @include('partials.item-reviews', ['item' => $item])
                            </div>
                        </div>
                    </div>

    @push('scripts')
    <script>
        // Auto-hide toast messages
        setTimeout(() => {
            const successToast = document.getElementById('toast-success');
            const errorToast = document.getElementById('toast-error');
            if (successToast) successToast.remove();
            if (errorToast) errorToast.remove();
        }, 5000);

        // Interactive star rating (for reviews partial)
        document.addEventListener('DOMContentLoaded', function() {
            const starButtons = document.querySelectorAll('.star-btn');
            const ratingInput = document.getElementById('ratingInput');
            
            if (!starButtons.length || !ratingInput) return;

            starButtons.forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    const rating = this.dataset.rating;
                    ratingInput.value = rating;
                    
                    // Update star colors
                    starButtons.forEach((star, starIndex) => {
                        const svg = star.querySelector('svg');
                        if (starIndex < rating) {
                            svg.classList.remove('text-gray-600');
                            svg.classList.add('text-yellow-400');
                        } else {
                            svg.classList.remove('text-yellow-400');
                            svg.classList.add('text-gray-600');
                        }
                    });
                });

                // Hover effect
                btn.addEventListener('mouseenter', function() {
                    const rating = this.dataset.rating;
                    starButtons.forEach((star, starIndex) => {
                        const svg = star.querySelector('svg');
                        if (starIndex < rating) {
                            svg.classList.add('text-yellow-300');
                        }
                    });
                });

                btn.addEventListener('mouseleave', function() {
                    const currentRating = ratingInput.value;
                    starButtons.forEach((star, starIndex) => {
                        const svg = star.querySelector('svg');
                        svg.classList.remove('text-yellow-300');
                        if (starIndex < currentRating) {
                            svg.classList.add('text-yellow-400');
                        }
                    });
                });
            });
        });
    </script>
    @endpush

@endsection
