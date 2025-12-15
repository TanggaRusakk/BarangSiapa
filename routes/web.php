<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemGalleryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceFeeController;
use App\Http\Controllers\RentalInfoController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\MidtransNotification;
use App\Http\Controllers\OrderServiceFeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdController::class, 'index']);

// Public API-like endpoints (simple index actions)
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::get('/galleries', [ItemGalleryController::class, 'index'])->name('galleries.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');
Route::get('/vendors/{vendor}', [VendorController::class, 'show'])->name('vendors.show');
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/order-items', [OrderItemController::class, 'index'])->name('order-items.index');
Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::get('/service-fees', [ServiceFeeController::class, 'index'])->name('service-fees.index');
Route::get('/rental-infos', [RentalInfoController::class, 'index'])->name('rental-infos.index');
Route::get('/item-categories', [ItemCategoryController::class, 'index'])->name('item-categories.index');
Route::get('/order-service-fees', [OrderServiceFeeController::class, 'index'])->name('order-service-fees.index');

use Illuminate\Http\Request;

Route::get('/dashboard', function (Request $request) {
    // Core datasets
    $recentProducts = \App\Models\Item::orderBy('created_at', 'desc')->take(6)->get();
    $lastViewed = null;
    if (session('last_viewed_item')) {
        $lastViewed = \App\Models\Item::find(session('last_viewed_item'));
    }

    // Metrics
    $totalUsers = \App\Models\User::count();
    $activeVendors = \App\Models\Vendor::count();
    $totalProducts = \App\Models\Item::count();

    // Revenue this month (sum payments)
    $revenueThisMonth = \App\Models\Payment::whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->sum('payment_total_amount');

    // Recent entities
    $recentUsers = \App\Models\User::orderBy('created_at', 'desc')->take(6)->get();
    $recentOrders = \App\Models\Order::orderBy('ordered_at', 'desc')->take(6)->get();

    // Vendor specific quick numbers (if current user is vendor)
    $vendorProductsCount = 0;
    if (auth()->check() && auth()->user()->vendor) {
        $vendorProductsCount = auth()->user()->vendor->items()->count();
    }

    return view('dashboard', compact(
        'recentProducts', 'lastViewed', 'totalUsers', 'activeVendors', 'totalProducts', 'revenueThisMonth', 'recentUsers', 'recentOrders', 'vendorProductsCount'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

// Endpoint to record last-viewed product (AJAX)
Route::post('/product/viewed', function (Request $request) {
    $id = $request->input('id');
    if ($id) {
        session(['last_viewed_item' => $id]);
    }
    return response()->json(['status' => 'ok']);
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo/upload', [ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
    Route::delete('/profile/photo/remove', [ProfileController::class, 'removePhoto'])->name('profile.photo.remove');

    // Admin: Users management
    Route::get('/admin/users', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $users = \App\Models\User::orderBy('created_at', 'desc')->paginate(25);
        return view('admin.users', compact('users'));
    })->name('admin.users');

    Route::get('/admin/users/{user}', function (\App\Models\User $user) {
        if (auth()->user()->role !== 'admin') abort(403);
        $orders = $user->orders()->with('orderItems.item')->orderBy('created_at', 'desc')->take(50)->get();
        $reviews = $user->reviews()->orderBy('created_at', 'desc')->take(50)->get();
        $payments = \App\Models\Payment::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(50)->get();
        return view('admin.user', compact('user', 'orders', 'reviews', 'payments'));
    })->name('admin.users.show');

    Route::delete('/admin/users/{user}', function (\App\Models\User $user) {
        if (auth()->user()->role !== 'admin') abort(403);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted');
    })->name('admin.users.destroy');

    // Admin: Vendors CRUD
    Route::get('/admin/vendors', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $vendors = \App\Models\Vendor::with('user')->paginate(25);
        return view('admin.vendors', compact('vendors'));
    })->name('admin.vendors');

    Route::get('/admin/vendors/create', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $users = \App\Models\User::where('role', 'vendor')->get();
        return view('admin.vendors-create', compact('users'));
    })->name('admin.vendors.create');

    Route::post('/admin/vendors', function (Request $request) {
        if (auth()->user()->role !== 'admin') abort(403);
        \App\Models\Vendor::create($request->all());
        return redirect()->route('admin.vendors')->with('success', 'Vendor created');
    })->name('admin.vendors.store');

    Route::get('/admin/vendors/{vendor}/edit', function (\App\Models\Vendor $vendor) {
        if (auth()->user()->role !== 'admin') abort(403);
        $users = \App\Models\User::where('role', 'vendor')->get();
        return view('admin.vendors-edit', compact('vendor', 'users'));
    })->name('admin.vendors.edit');

    Route::patch('/admin/vendors/{vendor}', function (Request $request, \App\Models\Vendor $vendor) {
        if (auth()->user()->role !== 'admin') abort(403);
        $vendor->update($request->all());
        return redirect()->route('admin.vendors')->with('success', 'Vendor updated');
    })->name('admin.vendors.update');

    Route::delete('/admin/vendors/{vendor}', function (\App\Models\Vendor $vendor) {
        if (auth()->user()->role !== 'admin') abort(403);
        $vendor->delete();
        return redirect()->route('admin.vendors')->with('success', 'Vendor deleted');
    })->name('admin.vendors.destroy');

    // Admin: Items/Products CRUD
    Route::get('/admin/items', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $items = \App\Models\Item::with('vendor')->paginate(25);
        return view('admin.items', compact('items'));
    })->name('admin.items');

    Route::get('/admin/items/create', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $vendors = \App\Models\Vendor::all();
        return view('admin.items-create', compact('vendors'));
    })->name('admin.items.create');

    Route::post('/admin/items', function (Request $request) {
        if (auth()->user()->role !== 'admin') abort(403);
        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_price' => 'required|numeric',
            'item_type' => 'required|in:jual,sewa',
            'item_status' => 'nullable|in:available,unavailable',
            'item_stock' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|max:4096',
            'vendor_id' => 'nullable|integer|exists:vendors,id',
        ]);

        $item = \App\Models\Item::create($data);

        // Handle uploaded images (store in public/images/item and create gallery rows)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $file->move(public_path('images/item'), $filename);
                \App\Models\ItemGallery::create([
                    'item_id' => $item->id,
                    'image_path' => $filename,
                ]);
            }
        }

        return redirect()->route('admin.items')->with('success', 'Item created');
    })->name('admin.items.store');

    Route::get('/admin/items/{item}/edit', function (\App\Models\Item $item) {
        if (auth()->user()->role !== 'admin') abort(403);
        $vendors = \App\Models\Vendor::all();
        return view('admin.items-edit', compact('item', 'vendors'));
    })->name('admin.items.edit');

    Route::patch('/admin/items/{item}', function (Request $request, \App\Models\Item $item) {
        if (auth()->user()->role !== 'admin') abort(403);
        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_price' => 'required|numeric',
            'item_type' => 'required|in:jual,sewa',
            'item_status' => 'nullable|in:available,unavailable',
            'item_stock' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|max:4096',
            'vendor_id' => 'nullable|integer|exists:vendors,id',
        ]);

        $item->update(collect($data)->only(['item_name','item_description','item_price','item_type','item_status','item_stock','vendor_id'])->toArray());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $file->move(public_path('images/item'), $filename);
                \App\Models\ItemGallery::create([
                    'item_id' => $item->id,
                    'image_path' => $filename,
                ]);
            }
        }

        return redirect()->route('admin.items')->with('success', 'Item updated');
    })->name('admin.items.update');

    Route::delete('/admin/items/{item}', function (\App\Models\Item $item) {
        if (auth()->user()->role !== 'admin') abort(403);
        $item->delete();
        return redirect()->route('admin.items')->with('success', 'Item deleted');
    })->name('admin.items.destroy');

    // Admin: Orders CRUD (read + update status)
    Route::get('/admin/orders', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $orders = \App\Models\Order::with('user')->orderBy('created_at', 'desc')->paginate(25);
        return view('admin.orders', compact('orders'));
    })->name('admin.orders');

    Route::get('/admin/orders/{order}', function (\App\Models\Order $order) {
        if (auth()->user()->role !== 'admin') abort(403);
        $order->load('orderItems.item', 'user');
        return view('admin.order', compact('order'));
    })->name('admin.orders.show');

    Route::patch('/admin/orders/{order}', function (Request $request, \App\Models\Order $order) {
        if (auth()->user()->role !== 'admin') abort(403);
        $order->update(['order_status' => $request->order_status]);
        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated');
    })->name('admin.orders.update');

    // Admin: Reviews CRUD (read + delete)
    Route::get('/admin/reviews', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $reviews = \App\Models\Review::with('user', 'item')->paginate(25);
        return view('admin.reviews', compact('reviews'));
    })->name('admin.reviews');

    Route::delete('/admin/reviews/{review}', function (\App\Models\Review $review) {
        if (auth()->user()->role !== 'admin') abort(403);
        $review->delete();
        return redirect()->route('admin.reviews')->with('success', 'Review deleted');
    })->name('admin.reviews.destroy');

    // Admin: Payments (read only)
    Route::get('/admin/payments', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $payments = \App\Models\Payment::with('user')->orderBy('created_at', 'desc')->paginate(25);
        return view('admin.payments', compact('payments'));
    })->name('admin.payments');

    Route::get('/admin/payments/{payment}', function (\App\Models\Payment $payment) {
        if (auth()->user()->role !== 'admin') abort(403);
        $payment->load('user');
        return view('admin.payment', compact('payment'));
    })->name('admin.payments.show');

    // Admin: Messages (read only)
    Route::get('/admin/messages', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $messages = \App\Models\Message::with('user', 'chat')->orderBy('created_at', 'desc')->paginate(50);
        return view('admin.messages', compact('messages'));
    })->name('admin.messages');

    // Admin: Ads CRUD
    Route::get('/admin/ads', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $ads = \App\Models\Ad::with('vendor')->paginate(25);
        return view('admin.ads', compact('ads'));
    })->name('admin.ads');

    Route::get('/admin/ads/create', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $vendors = \App\Models\Vendor::all();
        return view('admin.ads-create', compact('vendors'));
    })->name('admin.ads.create');

    Route::post('/admin/ads', function (Request $request) {
        if (auth()->user()->role !== 'admin') abort(403);
        \App\Models\Ad::create($request->all());
        return redirect()->route('admin.ads')->with('success', 'Ad created');
    })->name('admin.ads.store');

    Route::get('/admin/ads/{ad}/edit', function (\App\Models\Ad $ad) {
        if (auth()->user()->role !== 'admin') abort(403);
        $vendors = \App\Models\Vendor::all();
        return view('admin.ads-edit', compact('ad', 'vendors'));
    })->name('admin.ads.edit');

    Route::patch('/admin/ads/{ad}', function (Request $request, \App\Models\Ad $ad) {
        if (auth()->user()->role !== 'admin') abort(403);
        $ad->update($request->all());
        return redirect()->route('admin.ads')->with('success', 'Ad updated');
    })->name('admin.ads.update');

    Route::delete('/admin/ads/{ad}', function (\App\Models\Ad $ad) {
        if (auth()->user()->role !== 'admin') abort(403);
        $ad->delete();
        return redirect()->route('admin.ads')->with('success', 'Ad deleted');
    })->name('admin.ads.destroy');

    // Vendor routes (basic) — dashboard, product create/list, orders, ads
    Route::get('/vendor/dashboard', function () {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        $recentProducts = $vendor ? $vendor->items()->orderBy('created_at', 'desc')->take(6)->get() : collect();
        return view('vendor.dashboard', compact('recentProducts'));
    })->name('vendor.dashboard');

    Route::get('/vendor/products/create', function () {
        if (auth()->user()->role !== 'vendor') abort(403);
        return view('vendor.products-create');
    })->name('vendor.products.create');

    Route::post('/vendor/products', function (Request $request) {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_price' => 'required|numeric',
            'item_type' => 'required|string',
            'item_status' => 'nullable|string',
            'item_stock' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|max:4096',
        ]);
        $data['vendor_id'] = $vendor->id ?? null;

        $item = \App\Models\Item::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $file->move(public_path('images/item'), $filename);
                \App\Models\ItemGallery::create([
                    'item_id' => $item->id,
                    'image_path' => $filename,
                ]);
            }
        }

        return redirect()->route('vendor.products.list')->with('success', 'Product created');
    })->name('vendor.products.store');

    Route::get('/vendor/products/list', function () {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        $items = $vendor ? $vendor->items()->orderBy('created_at', 'desc')->paginate(25) : collect();
        return view('vendor.products-list', compact('items'));
    })->name('vendor.products.list');

    // Delete vendor product (ensure ownership)
    Route::delete('/vendor/products/{item}', function (Request $request, \App\Models\Item $item) {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        if (!$vendor || $item->vendor_id !== $vendor->id) abort(403);
        $item->delete();
        return redirect()->route('vendor.products.list')->with('success', 'Product deleted');
    })->name('vendor.products.destroy');

    Route::get('/vendor/orders/list', function () {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        $orders = \App\Models\Order::whereHas('orderItems.item', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id ?? 0);
        })->with('user')->orderBy('created_at', 'desc')->paginate(25);
        return view('vendor.orders-list', compact('orders'));
    })->name('vendor.orders.list');

    Route::get('/vendor/ads/create', function () {
        if (auth()->user()->role !== 'vendor') abort(403);
        return view('vendor.ads-create');
    })->name('vendor.ads.create');

    Route::post('/vendor/ads', function (Request $request) {
        if (auth()->user()->role !== 'vendor') abort(403);
        $data = $request->validate([
            'ad_title' => 'required|string',
            'ad_image' => 'nullable|string',
            'ad_url' => 'nullable|url',
        ]);
        $data['vendor_id'] = auth()->user()->vendor->id ?? null;
        \App\Models\Ad::create($data);
        return redirect()->route('vendor.dashboard')->with('success', 'Ad created');
    })->name('vendor.ads.store');

    // Edit vendor product
    Route::get('/vendor/products/{item}/edit', function (\App\Models\Item $item) {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        if (!$vendor || $item->vendor_id !== $vendor->id) abort(403);
        return view('vendor.products-edit', compact('item'));
    })->name('vendor.products.edit');

    Route::patch('/vendor/products/{item}', function (Request $request, \App\Models\Item $item) {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        if (!$vendor || $item->vendor_id !== $vendor->id) abort(403);
        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_price' => 'required|numeric',
            'item_type' => 'required|in:jual,sewa',
            'item_status' => 'nullable|in:available,unavailable',
            'item_stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload: store to public/images/item and create gallery record
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\-_.]/', '_', $file->getClientOriginalName());
            $dest = public_path('images/item');
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
            $file->move($dest, $filename);

            // create gallery record
            \App\Models\ItemGallery::create([
                'item_id' => $item->id,
                'image_path' => $filename,
            ]);
        }

        // Update item fields
        $item->update(collect($data)->only(['item_name','item_description','item_price','item_type','item_status','item_stock'])->toArray());
        return redirect()->route('vendor.products.list')->with('success', 'Product updated');
    })->name('vendor.products.update');
});

Route::get('/payment', [App\Http\Controllers\PaymentController::class, 'payment']);

Route::post('/midtrans/notification', [MidtransNotification::class, 'handle'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

require __DIR__.'/auth.php';

// Temporary debug route: show resolved image URLs and placeholder existence
Route::get('/debug-image-urls', function () {
    $userUrl = auth()->check() ? auth()->user()->photo_url : null;
    $item = \App\Models\Item::with('galleries')->first();
    $itemUrl = $item ? $item->first_image_url : null;

    return response()->json([
        'user_photo_url' => $userUrl,
        'item_first_image_url' => $itemUrl,
        'profile_placeholder_jpg' => file_exists(public_path('images/profiles/profile_placeholder.jpg')),
        'profile_placeholder_png' => file_exists(public_path('images/profiles/profile_placeholder.png')),
        'item_placeholder_jpg' => file_exists(public_path('images/items/item_placeholder.jpg')),
        'item_placeholder_png' => file_exists(public_path('images/items/item_placeholder.png')),
    ]);
});