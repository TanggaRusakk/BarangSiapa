@extends('layouts.mainlayout')

@section('content')

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="toast-success" class="fixed top-20 right-4 z-50 bg-green-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('toast-success').remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div id="toast-error" class="fixed top-20 right-4 z-50 bg-red-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('error') }}</span>
            <button onclick="document.getElementById('toast-error').remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 item-detail">
        <!-- Breadcrumb -->
        <div class="mb-6 text-sm text-soft-lilac">
            <a href="{{ url('/') }}" class="hover:text-neon-pink">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('items.index') }}" class="hover:text-neon-pink">Items</a>
            <span class="mx-2">/</span>
            <span class="text-white">{{ $item->item_name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Images & Main Info -->
            <div class="lg:col-span-2">
                <!-- Image Gallery -->
                <div class="card mb-6">
                    @if($item->galleries->count() > 0)
                        <div class="mb-4">
                            @php
                                $firstGallery = $item->galleries->first();
                                $mainImage = null;
                                if ($firstGallery && $firstGallery->image_path && file_exists(public_path('images/items/' . $firstGallery->image_path))) {
                                    $mainImage = asset('images/items/' . $firstGallery->image_path);
                                } else {
                                    $mainImage = file_exists(public_path('images/items/item_placeholder.jpg')) ? asset('images/items/item_placeholder.jpg') : asset('images/items/item_placeholder.png');
                                }
                            @endphp
                            <img id="main-image" src="{{ $mainImage }}" 
                                 alt="{{ $item->item_name }}"
                                 class="w-full h-96 object-cover rounded-lg">
                        </div>
                        
                        @if($item->galleries->count() > 1)
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($item->galleries as $gallery)
                                    @php
                                        $thumb = ($gallery->image_path && file_exists(public_path('images/items/' . $gallery->image_path)))
                                            ? asset('images/items/' . $gallery->image_path)
                                            : (file_exists(public_path('images/items/item_placeholder.jpg')) ? asset('images/items/item_placeholder.jpg') : asset('images/items/item_placeholder.png'));
                                    @endphp
                                    <img src="{{ $thumb }}" 
                                         alt="Gallery"
                                         class="w-full h-24 object-cover rounded cursor-pointer border-2 border-transparent hover:border-neon-pink transition"
                                         onclick="document.getElementById('main-image').src=''+this.src">
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="w-full h-96 bg-midnight-black rounded-lg flex items-center justify-center main-placeholder">
                            <svg class="w-16 h-16 text-soft-lilac" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Item Details -->
                <div class="card">
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
                        <div class="mb-4 text-sm text-soft-lilac">
                            <a href="{{ url('/') }}" class="hover:text-neon-pink">Home</a>
                            <span class="mx-2">/</span>
                            <a href="{{ route('items.index') }}" class="hover:text-neon-pink">Items</a>
                            <span class="mx-2">/</span>
                            <span class="text-white">{{ $item->item_name }}</span>
                        </div>

                        <!-- Top row: Image | Details -->
                        <div class="flex flex-col lg:flex-row gap-6">
                            <!-- Image column -->
                            <div class="lg:w-1/2">
                                <div class="card">
                                    @if($item->galleries->count() > 0)
                                        @php
                                            $firstGallery = $item->galleries->first();
                                            $mainImage = null;
                                            if ($firstGallery && $firstGallery->image_path && file_exists(public_path('images/items/' . $firstGallery->image_path))) {
                                                $mainImage = asset('images/items/' . $firstGallery->image_path);
                                            } else {
                                                $mainImage = file_exists(public_path('images/items/item_placeholder.jpg')) ? asset('images/items/item_placeholder.jpg') : asset('images/items/item_placeholder.png');
                                            }
                                        @endphp
                                        <div class="mb-4">
                                            <img id="main-image" src="{{ $mainImage }}" alt="{{ $item->item_name }}" class="w-full h-96 object-cover rounded-lg">
                                        </div>

                                        @if($item->galleries->count() > 1)
                                            <div class="grid grid-cols-4 gap-2">
                                                @foreach($item->galleries as $gallery)
                                                    @php
                                                        $thumb = ($gallery->image_path && file_exists(public_path('images/items/' . $gallery->image_path)))
                                                            ? asset('images/items/' . $gallery->image_path)
                                                            : (file_exists(public_path('images/items/item_placeholder.jpg')) ? asset('images/items/item_placeholder.jpg') : asset('images/items/item_placeholder.png'));
                                                    @endphp
                                                    <img src="{{ $thumb }}" alt="Gallery" class="w-full h-24 object-cover rounded cursor-pointer border-2 border-transparent hover:border-neon-pink transition" onclick="document.getElementById('main-image').src=this.src">
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-full h-96 bg-midnight-black rounded-lg flex items-center justify-center main-placeholder">
                                            <svg class="w-16 h-16 text-soft-lilac" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Details column -->
                            <div class="lg:w-1/2">
                                <div class="card p-6">
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
                                        <p class="text-soft-lilac leading-relaxed">{{ $item->item_description ?? 'No description available for this item.' }}</p>
                                    </div>

                                    @if($item->item_type === 'rent')
                                        <div class="bg-royal-purple bg-opacity-10 border border-royal-purple border-opacity-30 rounded-lg p-3 mb-4">
                                            <h4 class="font-semibold text-white mb-1">Rental Information</h4>
                                            <p class="text-soft-lilac text-sm">Rp {{ number_format($item->item_price, 0, ',', '.') }} per day</p>
                                        </div>
                                    @endif

                                    <div class="flex gap-3">
                                        @auth
                                            @if($item->item_status === 'available' && $item->item_stock > 0)
                                                <a href="{{ route('checkout', $item->id) }}" class="btn btn-primary flex-1 text-center">
                                                    {{ $item->item_type === 'rent' ? 'Rent Now' : 'Order Now' }}
                                                </a>
                                            @else
                                                <button class="btn btn-secondary flex-1" disabled>Out of Stock</button>
                                            @endif
                                            <button class="btn bg-midnight-black border-2 border-royal-purple hover:border-neon-pink">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary flex-1 text-center">Login to {{ $item->item_type === 'rent' ? 'Rent' : 'Purchase' }}</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vendor info (full width) -->
                        <div class="mt-6">
                            <div class="card p-4">
                                <h3 class="text-lg font-bold text-white mb-3">Sold By</h3>
                                @if($item->vendor)
                                    <div class="flex items-center gap-4">
                                        @php
                                            $vendorLogo = null;
                                            if ($item->vendor && $item->vendor->logo_path && file_exists(public_path('images/vendor/' . $item->vendor->logo_path))) {
                                                $vendorLogo = asset('images/vendor/' . $item->vendor->logo_path);
                                            } else {
                                                $vendorLogo = file_exists(public_path('images/vendor/vendor_placeholder.jpg')) ? asset('images/vendor/vendor_placeholder.jpg') : asset('images/vendor/vendor_placeholder.png');
                                            }
                                        @endphp
                                        <div class="w-20 h-20 rounded-lg overflow-hidden">
                                            @if($vendorLogo)
                                                <img src="{{ $vendorLogo }}" alt="{{ $item->vendor->vendor_name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-royal-purple flex items-center justify-center text-neon-pink font-bold">{{ substr($item->vendor->vendor_name,0,1) }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-white">{{ $item->vendor->vendor_name }}</h4>
                                            <p class="text-sm text-soft-lilac">{{ $item->vendor->location ?? 'Not specified' }}</p>
                                        </div>
                                        <div class="ms-auto">
                                            <a href="{{ route('vendors.show', $item->vendor) }}" class="btn btn-primary">View All Products</a>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-soft-lilac">Vendor information not available.</p>
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
