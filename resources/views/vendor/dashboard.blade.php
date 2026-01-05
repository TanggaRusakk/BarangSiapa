<x-dashboard-layout>
    <x-slot name="title">Vendor Dashboard</x-slot>

    <div class="container-fluid" style="max-width: 1400px;">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gradient">Hi, {{ auth()->user()->name }} 👋</h2>
            <p class="text-sm text-soft-lilac">Manage your store — products, orders and earnings.</p>
        </div>

        <!-- Stats Grid -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-4">
            <div class="card subtle-hover p-6">
                <div class="stat-label">My Products</div>
                <div class="stat-value text-3xl font-extrabold">{{ $productsCount ?? 0 }}</div>
                <div class="text-sm text-soft-lilac mt-2">Active & draft items</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card subtle-hover p-6">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value text-3xl font-extrabold">{{ $ordersCount ?? 0 }}</div>
                <div class="text-sm text-soft-lilac mt-2">Excluding cancelled orders</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card subtle-hover p-6">
                <div class="stat-label">Total Sales</div>
                <div class="stat-value text-3xl font-extrabold">Rp{{ number_format($revenue ?? 0) }}</div>
                <div class="text-sm text-soft-lilac mt-2">From completed orders</div>
            </div>
            </div>
        </div>

        <!-- Store Rating Card -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card subtle-hover p-6 text-center">
                    <div class="stat-label">Store Rating</div>
                    <div class="stat-value text-3xl font-extrabold">
                        <span class="text-gradient">{{ $storeRating ?? 0 }}</span>
                        <span class="text-2xl text-soft-lilac">/ 5.0</span>
                    </div>
                    <div class="text-sm text-soft-lilac mt-2">Average rating from all products</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions: clear 3-column boxes -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('vendor.products.create') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center mb-3" style="min-height: 140px;">
                <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                <h3 class="font-bold text-lg mb-1">Add New Product</h3>
                <p class="text-sm text-secondary mb-0">Create product listing</p>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('vendor.products.list') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center mb-3" style="min-height: 140px;">
                <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                </svg>
                <h3 class="font-bold text-lg mb-1">My Products</h3>
                <p class="text-sm text-secondary mb-0">View & manage all items</p>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
                <a href="{{ route('vendor.orders.list') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center mb-3" style="min-height: 140px;">
                <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                <h3 class="font-bold text-lg mb-1">Orders</h3>
                <p class="text-sm text-secondary mb-0">Manage recent orders</p>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('vendor.ads.index') }}" class="card subtle-hover text-center p-4 d-flex flex-column align-items-center justify-content-center mb-3" style="min-height: 140px;">
                <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                    <line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>
                </svg>
                <h3 class="font-bold text-lg mb-1">My Ads</h3>
                <p class="text-sm text-secondary mb-0">Create & manage ads</p>
            </a>
        </div>
    </div>

    <!-- Recent Products & Orders (2-column layout) -->
    <div class="row g-3">
        <!-- Recent Products -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h5 fw-bold mb-0">Products</h3>
                        <a href="{{ route('vendor.products.list') }}" class="btn btn-sm" style="background: #6A38C2; color: white;">VIEW ALL</a>
                    </div>

                    @if(isset($recentProducts) && $recentProducts->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($recentProducts->take(5) as $product)
                                <div class="d-flex align-items-center p-3 rounded" style="background: rgba(250,250,250,0.8);">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $product->first_image_url }}" alt="{{ $product->item_name }}" class="rounded" style="width:56px;height:56px;object-fit:cover;">
                                    </div>

                                    <div class="flex-grow-1 ms-3 min-w-0">
                                        <h6 class="mb-1 fw-bold text-truncate">{{ $product->item_name }}</h6>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0 text-secondary small text-truncate">
                                                @if(($product->item_type ?? '') === 'rent')
                                                    Rp{{ number_format($product->item_price) }} / day
                                                @else
                                                    Rp{{ number_format($product->item_price) }} (Buy)
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <svg class="mb-3 text-secondary" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.3">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                            </svg>
                            <p class="text-secondary mb-0">No products yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Ads -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h5 fw-bold mb-0">Ads</h3>
                        <a href="{{ route('vendor.ads.index') }}" class="btn btn-sm" style="background: #FF3CAC; color: white;">VIEW ALL</a>
                    </div>

                    @if(isset($recentAds) && $recentAds->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($recentAds as $ad)
                                <div class="d-flex gap-3 p-3 rounded" style="background: rgba(255,60,172,0.05);">
                                    @if($ad->ad_image)
                                        <img src="{{ asset('storage/' . $ad->ad_image) }}" alt="Ad Image" class="rounded" style="width:80px;height:80px;object-fit:cover;">
                                    @elseif($ad->item)
                                        <img src="{{ $ad->item->first_image_url }}" alt="{{ $ad->item->item_name }}" class="rounded" style="width:80px;height:80px;object-fit:cover;">
                                    @else
                                        <div class="rounded d-flex align-items-center justify-content-center" style="width:80px;height:80px;background:#f0f0f0;">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                                                <line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $ad->item->item_name ?? 'Ad #' . $ad->id }}</h6>
                                        <p class="mb-1 text-secondary small">Rp{{ number_format($ad->price) }} • {{ \Carbon\Carbon::parse($ad->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($ad->end_date)->format('d M Y') }}</p>
                                        <span class="badge {{ $ad->status === 'active' ? 'bg-success' : ($ad->status === 'pending' ? 'bg-warning' : 'bg-secondary') }} small">{{ ucfirst($ad->status ?? 'pending') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <svg class="mb-3 text-secondary" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.3">
                                <path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                                <line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>
                            </svg>
                            <p class="text-secondary mb-0">No ads yet</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
