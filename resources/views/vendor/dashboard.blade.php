<x-dashboard-layout>
    <x-slot name="title">Vendor Dashboard</x-slot>

    <div class="mb-4">
        <h1 class="text-3xl font-bold text-gradient">Welcome, {{ auth()->user()->name }}</h1>
        <p class="text-soft-lilac">Quick overview of your store</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <a href="{{ route('vendor.products.create') }}" class="card text-center hover:scale-105 transition">
            <div class="text-4xl mb-2">➕</div>
            <h3 class="font-bold">Add Product</h3>
        </a>
        <a href="{{ route('vendor.products.list') }}" class="card text-center hover:scale-105 transition">
            <div class="text-4xl mb-2">📦</div>
            <h3 class="font-bold">My Products</h3>
        </a>
        <a href="{{ route('vendor.orders.list') }}" class="card text-center hover:scale-105 transition">
            <div class="text-4xl mb-2">🧾</div>
            <h3 class="font-bold">Orders</h3>
        </a>
    </div>

    <div class="card">
        <h3 class="text-xl font-bold mb-3">Products</h3>
        <div class="p-6 bg-purple-900 bg-opacity-5 rounded-md flex items-center justify-between">
            <div>
                <p class="font-semibold">Manage all your listed products from a single page.</p>
                <p class="text-sm text-soft-lilac">Click the button to view, create, or edit items.</p>
            </div>
            <div>
                <a href="{{ route('vendor.products.list') }}" class="btn btn-primary">View All Products</a>
            </div>
        </div>
    </div>

</x-dashboard-layout>

<script>
function quickView(id) {
    try {
        fetch('/items/' + id)
            .then(r => r.json())
            .then(data => {
                alert('Quick view:\n' + (data.item_name || data.item_name) + '\nPrice: ' + (data.item_price || '—'));
            }).catch(() => alert('Unable to fetch product details'));
    } catch (e) { alert('Unable to fetch product details'); }
}
</script>
