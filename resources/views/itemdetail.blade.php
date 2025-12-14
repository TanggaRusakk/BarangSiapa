@extends('layouts.mainlayout')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                            <img id="main-image" src="{{ asset('images/items/' . $item->galleries->first()->image_path) }}" 
                                 alt="{{ $item->item_name }}"
                                 class="w-full h-96 object-cover rounded-lg">
                        </div>
                        
                        @if($item->galleries->count() > 1)
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($item->galleries as $gallery)
                                    <img src="{{ asset('images/items/' . $gallery->image_path) }}" 
                                         alt="Gallery"
                                         class="w-full h-24 object-cover rounded cursor-pointer border-2 border-transparent hover:border-neon-pink transition"
                                         onclick="document.getElementById('main-image').src=this.src">
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="w-full h-96 bg-midnight-black rounded-lg flex items-center justify-center">
                            <svg class="w-24 h-24 text-soft-lilac" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-white mb-2">Description</h3>
                        <p class="text-soft-lilac leading-relaxed">
                            {{ $item->item_description ?? 'No description available for this item.' }}
                        </p>
                    </div>

                    @if($item->item_type === 'rent')
                        <div class="bg-royal-purple bg-opacity-10 border border-royal-purple border-opacity-30 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold text-white mb-2">Rental Information</h4>
                            <p class="text-soft-lilac text-sm">This item is available for rent at Rp {{ number_format($item->item_price, 0, ',', '.') }} per day.</p>
                        </div>
                    @endif

                    <div class="flex gap-3">
                        @auth
                            <button class="btn btn-primary flex-1">
                                {{ $item->item_type === 'rent' ? 'Rent Now' : 'Add to Cart' }}
                            </button>
                            <button class="btn bg-midnight-black border-2 border-royal-purple hover:border-neon-pink">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary flex-1 text-center">
                                Login to {{ $item->item_type === 'rent' ? 'Rent' : 'Purchase' }}
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="card mt-6">
                    <h2 class="text-2xl font-bold text-white mb-6">Customer Reviews</h2>
                    
                    @if($item->reviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($item->reviews as $review)
                                <div class="border-b border-royal-purple border-opacity-30 pb-6 last:border-0">
                                    <div class="flex items-start gap-4">
                                        <!-- User Avatar -->
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 rounded-full bg-royal-purple bg-opacity-30 flex items-center justify-center text-neon-pink font-bold">
                                                {{ substr($review->user->name ?? 'U', 0, 1) }}
                                            </div>
                                        </div>

                                        <div class="flex-1">
                                            <!-- User & Rating -->
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="font-semibold text-white">{{ $review->user->name ?? 'Anonymous' }}</span>
                                                <div class="flex items-center gap-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= ($review->rating ?? 0) ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-xs text-soft-lilac">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>

                                            <!-- Review Title -->
                                            @if($review->title)
                                                <h4 class="font-semibold text-white mb-1">{{ $review->title }}</h4>
                                            @endif

                                            <!-- Review Content -->
                                            <p class="text-soft-lilac leading-relaxed">
                                                {{ $review->review ?? 'No review content.' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-soft-lilac">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            <p>No reviews yet. Be the first to review this item!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Vendor Info -->
                <div class="lg:col-span-1">
                    <div class="card sticky top-20">
                        <h3 class="text-lg font-bold text-white mb-4">Sold By</h3>

                        @if($item->vendor)
                            <a href="{{ route('vendors.show', $item->vendor) }}" class="block hover:bg-purple-900 hover:bg-opacity-10 p-3 rounded transition">
                                <div class="mb-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-16 h-16 rounded-full bg-royal-purple bg-opacity-30 flex items-center justify-center overflow-hidden">
                                            @if($item->vendor->logo_path)
                                                <img src="{{ asset('images/vendor/' . $item->vendor->logo_path) }}" alt="{{ $item->vendor->vendor_name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-2xl font-bold text-neon-pink">{{ substr($item->vendor->vendor_name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-white">{{ $item->vendor->vendor_name }}</h4>
                                            <p class="text-xs text-soft-lilac">Verified Vendor</p>
                                        </div>
                                    </div>

                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-start gap-2 text-sm">
                                            <svg class="w-5 h-5 text-neon-pink flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <div>
                                                <span class="text-soft-lilac block">Location</span>
                                                <span class="text-white">{{ $item->vendor->location ?? 'Not specified' }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-2 text-sm">
                                            <svg class="w-5 h-5 text-neon-pink flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            <div>
                                                <span class="text-soft-lilac block">Products</span>
                                                <span class="text-white">{{ $item->vendor->items->count() }} items</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($item->vendor->description)
                                        <div class="mb-6">
                                            <h5 class="text-sm font-semibold text-white mb-2">About</h5>
                                            <p class="text-sm text-soft-lilac leading-relaxed">{{ $item->vendor->description }}</p>
                                        </div>
                                    @endif

                                    <div class="space-y-2">
                                        <a href="{{ route('vendors.show', $item->vendor) }}" class="btn btn-primary w-full text-center">View All Products</a>
                                        <button class="btn bg-midnight-black border-2 border-royal-purple hover:border-neon-pink w-full">Contact Vendor</button>
                                    </div>
                                </div>
                            </a>
                        @else
                            <p class="text-soft-lilac text-sm">Vendor information not available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
