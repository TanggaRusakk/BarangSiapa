<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">{{ number_format($totalUsers ?? 0) }}</h3>
                <p class="text-muted mb-0 small">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">{{ number_format($activeVendors ?? 0) }}</h3>
                <p class="text-muted mb-0 small">Active Vendors</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">{{ number_format($totalProducts ?? 0) }}</h3>
                <p class="text-muted mb-0 small">Total Products</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">{{ number_format($totalCategories ?? 0) }}</h3>
                <p class="text-muted mb-0 small">Categories</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <h3 class="fw-bold mb-1" style="font-size: 2rem; color: #6A38C2;">Rp{{ number_format($revenueThisMonth ?? 0) }}</h3>
                <p class="text-muted mb-0 small">Revenue (This month)</p>
            </div>
        </div>
    </div>
</div>
