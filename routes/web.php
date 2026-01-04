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

// Midtrans notification webhook
Route::get('/service-fees', [ServiceFeeController::class, 'index'])->name('service-fees.index');
Route::get('/rental-infos', [RentalInfoController::class, 'index'])->name('rental-infos.index');
Route::get('/item-categories', [ItemCategoryController::class, 'index'])->name('item-categories.index');
Route::get('/order-service-fees', [OrderServiceFeeController::class, 'index'])->name('order-service-fees.index');

use Illuminate\Http\Request;

Route::get('/dashboard', function (Request $request) {
    $user = auth()->user();
    
    // Core datasets
    $recentProducts = \App\Models\Item::orderBy('created_at', 'desc')->take(6)->get();
    $lastViewed = null;
    if (session('last_viewed_item')) {
        $lastViewed = \App\Models\Item::find(session('last_viewed_item'));
    }

    // Admin metrics
    $totalUsers = \App\Models\User::count();
    $activeVendors = \App\Models\Vendor::count();
    $totalProducts = \App\Models\Item::count();
    $revenueThisMonth = \App\Models\Payment::whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->where('payment_status', 'settlement')
        ->sum('payment_total_amount');
    $recentUsers = \App\Models\User::orderBy('created_at', 'desc')->take(6)->get();
    $recentOrders = \App\Models\Order::with(['user', 'orderItems.item.vendor'])->orderBy('created_at', 'desc')->take(6)->get();

    // Vendor specific
    $vendorProductsCount = 0;
    if ($user->vendor) {
        $vendorProductsCount = $user->vendor->items()->count();
    }

    // Member specific - recent orders and rentals
    $userOrders = \App\Models\Order::with(['orderItems.item'])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    
    $userRentals = \App\Models\Order::with(['orderItems.item'])
        ->where('user_id', $user->id)
        ->whereHas('orderItems.item', function($q) {
            $q->whereIn('item_type', ['rent', 'sewa']);
        })
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    
    // Member stats
    $activeOrdersCount = \App\Models\Order::where('user_id', $user->id)
        ->whereIn('order_status', ['pending', 'processing', 'paid'])
        ->count();
    
    $activeRentalsCount = \App\Models\Order::where('user_id', $user->id)
        ->whereIn('order_status', ['pending', 'processing', 'paid'])
        ->whereHas('orderItems.item', function($q) {
            $q->whereIn('item_type', ['rent', 'sewa']);
        })
        ->count();
    
    $totalSpent = \App\Models\Payment::where('user_id', $user->id)
        ->where('payment_status', 'settlement')
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', '>=', now()->subDays(30))
        ->sum('payment_total_amount');
    
    $reviewsGiven = \App\Models\Review::where('user_id', $user->id)->count();

    return view('dashboard', compact(
        'recentProducts', 'lastViewed', 'totalUsers', 'activeVendors', 'totalProducts', 
        'revenueThisMonth', 'recentUsers', 'recentOrders', 'vendorProductsCount',
        'userOrders', 'userRentals', 'activeOrdersCount', 'activeRentalsCount', 
        'totalSpent', 'reviewsGiven'
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

    // Order routes
    Route::get('/checkout/{item}', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my-orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Payment routes - UI callbacks only (authenticated)
    Route::get('/payment/{order}', [PaymentController::class, 'create'])->name('payment.create');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/pending', [PaymentController::class, 'pending'])->name('payment.pending');
    Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
});

// WHY: Webhook dipindah ke routes/api.php
// Lihat: routes/api.php untuk endpoint /api/midtrans/webhook

Route::middleware('auth')->group(function () {

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

        // Handle uploaded images (store in public/images/products and create gallery rows)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $file->move(public_path('images/products'), $filename);
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
                $file->move(public_path('images/products'), $filename);
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

    // Admin: Ads View-Only (vendors manage their own ads)
    Route::get('/admin/ads', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $ads = \App\Models\Ad::with(['item.vendor', 'payment'])->paginate(25);
        return view('admin.ads', compact('ads'));
    })->name('admin.ads');

    // Admin: Categories CRUD
    Route::get('/admin/categories', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        $categories = \App\Models\Category::orderBy('category_name')->paginate(25);
        return view('admin.categories', compact('categories'));
    })->name('admin.categories');

    Route::post('/admin/categories', function (Request $request) {
        if (auth()->user()->role !== 'admin') abort(403);
        $data = $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name'
        ]);
        \App\Models\Category::create($data);
        return redirect()->route('admin.categories')->with('success', 'Category created');
    })->name('admin.categories.store');

    Route::get('/admin/categories/{category}/edit', function (\App\Models\Category $category) {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('admin.categories-edit', compact('category'));
    })->name('admin.categories.edit');

    Route::patch('/admin/categories/{category}', function (Request $request, \App\Models\Category $category) {
        if (auth()->user()->role !== 'admin') abort(403);
        $data = $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $category->id
        ]);
        $category->update($data);
        return redirect()->route('admin.categories')->with('success', 'Category updated');
    })->name('admin.categories.update');

    Route::delete('/admin/categories/{category}', function (\App\Models\Category $category) {
        if (auth()->user()->role !== 'admin') abort(403);
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Category deleted');
    })->name('admin.categories.destroy');

    // Message routes (Shopee-like chat)
    Route::get('/messages', function () {
        $user = auth()->user();
        // Get all chats for this user (either as user_id or vendor_user_id)
        $chats = \App\Models\Chat::where('user_id', $user->id)
            ->orWhere('vendor_user_id', $user->id)
            ->with(['user', 'vendorUser', 'messages' => function($q) {
                $q->latest()->take(1);
            }])
            ->orderBy('last_message_at', 'desc')
            ->get();
        
        return view('messages.index', compact('chats'));
    })->name('messages.index');

    Route::get('/messages/start', function (Request $request) {
        $vendorId = $request->query('vendor_id');
        $itemId = $request->query('item_id');
        
        if (!$vendorId) {
            return redirect()->back()->with('error', 'Vendor not found');
        }
        
        // Find the vendor user
        $vendor = \App\Models\Vendor::findOrFail($vendorId);
        $vendorUser = $vendor->user;
        
        if (!$vendorUser) {
            return redirect()->back()->with('error', 'Vendor user not found');
        }
        
        // Check if chat already exists between these two users
        $chat = \App\Models\Chat::where(function($q) use ($vendorUser) {
                $q->where('user_id', auth()->id())
                  ->where('vendor_user_id', $vendorUser->id);
            })
            ->orWhere(function($q) use ($vendorUser) {
                $q->where('user_id', $vendorUser->id)
                  ->where('vendor_user_id', auth()->id());
            })
            ->first();
        
        // Prepare initial message (will be pre-filled in chat input)
        $initialMessage = null;
        if ($itemId) {
            $item = \App\Models\Item::find($itemId);
            $initialMessage = "Hi, I'm interested in: " . ($item ? $item->item_name : "your item");
        } else {
            $initialMessage = "Hi, I have a question about your products.";
        }
        
        // If no chat exists, create new one (without sending message)
        if (!$chat) {
            $chat = \App\Models\Chat::create([
                'user_id' => auth()->id(),
                'vendor_user_id' => $vendorUser->id,
                'last_message_at' => now()
            ]);
        }
        
        // Redirect with initial message as query parameter
        return redirect()->route('messages.show', ['chat' => $chat->id, 'initial_message' => $initialMessage]);
    })->name('messages.start');

    Route::get('/messages/{chat}', function (\App\Models\Chat $chat) {
        $user = auth()->user();
        
        // Verify user has access to this chat (either as user_id or vendor_user_id)
        $hasAccess = $chat->user_id === $user->id || $chat->vendor_user_id === $user->id;
        
        if (!$hasAccess) {
            abort(403, 'Unauthorized access to this chat');
        }
        
        // Get messages
        $messages = $chat->messages()->with('user')->orderBy('sent_at')->get();
        
        // Mark messages as read
        $chat->messages()
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        // Determine other user (the person we're chatting with)
        $otherUser = $chat->user_id === $user->id 
            ? $chat->vendorUser  // I'm the user, show vendor
            : $chat->user;       // I'm the vendor, show user
        
        // Get all chats for sidebar
        $allChats = \App\Models\Chat::where('user_id', $user->id)
            ->orWhere('vendor_user_id', $user->id)
            ->with(['user', 'vendorUser', 'messages' => function($q) {
                $q->latest()->take(1);
            }])
            ->orderBy('last_message_at', 'desc')
            ->get();
        
        return view('messages.show', compact('chat', 'messages', 'otherUser', 'allChats'));
    })->name('messages.show');

    Route::post('/messages/send', function (Request $request) {
        $data = $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'content' => 'required|string|max:2000'
        ]);
        
        $chat = \App\Models\Chat::findOrFail($data['chat_id']);
        
        // Create message
        $message = \App\Models\Message::create([
            'chat_id' => $chat->id,
            'user_id' => auth()->id(),
            'content' => $data['content'],
            'sent_at' => now(),
            'is_read' => false
        ]);
        
        // Update chat last_message_at
        $chat->update(['last_message_at' => now()]);
        
        // Broadcast the message to WebSocket
        broadcast(new \App\Events\MessageSent($message))->toOthers();
        
        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'user_id' => $message->user_id,
                'sent_at' => $message->sent_at->format('H:i'),
                'created_at' => $message->created_at->toIso8601String()
            ]
        ]);
    })->name('messages.send');

    Route::get('/messages/{chat}/fetch', function (\App\Models\Chat $chat) {
        $user = auth()->user();
        
        // Verify access
        $hasAccess = $chat->user_id === $user->id || 
                     $chat->messages()->where('user_id', $user->id)->exists();
        
        if (!$hasAccess) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $messages = $chat->messages()
            ->with('user')
            ->orderBy('sent_at')
            ->get()
            ->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'content' => $msg->content,
                    'user_id' => $msg->user_id,
                    'sent_at' => $msg->sent_at->format('H:i'),
                    'created_at' => $msg->created_at->toIso8601String(),
                    'user_name' => $msg->user->name ?? 'Unknown'
                ];
            });
        
        return response()->json(['messages' => $messages]);
    })->name('messages.fetch');

    // Review routes (only for users)
    Route::post('/reviews', function (Request $request) {
        if (auth()->user()->role !== 'user') {
            return redirect()->back()->with('error', 'Only users can write reviews');
        }

        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000'
        ]);

        // Check if user already reviewed this item
        $existingReview = \App\Models\Review::where('item_id', $data['item_id'])
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this item. Please edit your existing review.');
        }

        $data['user_id'] = auth()->id();
        \App\Models\Review::create($data);

        return redirect()->back()->with('success', 'Review submitted successfully!');
    })->name('reviews.store');

    Route::patch('/reviews/{review}', function (Request $request, \App\Models\Review $review) {
        // Check if user owns this review
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized to edit this review');
        }

        if (auth()->user()->role !== 'user') {
            return redirect()->back()->with('error', 'Only users can edit reviews');
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000'
        ]);

        $review->update($data);

        return redirect()->back()->with('success', 'Review updated successfully!');
    })->name('reviews.update');

    Route::delete('/reviews/{review}', function (\App\Models\Review $review) {
        // Check if user owns this review
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized to delete this review');
        }

        if (auth()->user()->role !== 'user') {
            return redirect()->back()->with('error', 'Only users can delete reviews');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully!');
    })->name('reviews.destroy');

    // Vendor routes (basic) — dashboard, product create/list, orders, ads
    Route::get('/vendor/dashboard', function () {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;

        // Recent products (for display)
        $recentProducts = $vendor ? $vendor->items()->orderBy('created_at', 'desc')->take(6)->get() : collect();

        // Orders related to this vendor (orders that contain items belonging to vendor)
        if ($vendor) {
            // Get ALL orders that have ANY of vendor's items (simpler approach)
            $vendorItems = $vendor->items()->pluck('id');
            
            $allOrders = \App\Models\Order::with(['user','orderItems.item'])->get();
            $vendorOrders = $allOrders->filter(function($order) use ($vendorItems) {
                return $order->orderItems->pluck('item_id')->intersect($vendorItems)->isNotEmpty();
            });

            // Total Orders (exclude cancelled)
            $ordersCount = $vendorOrders->where('order_status', '!=', 'cancelled')->count();
            
            // Recent 3 completed orders (newest first)
            $recentOrders = $vendorOrders
                ->where('order_status', 'completed')
                ->sortByDesc('created_at')
                ->take(3);

            // Total Sales: revenue from completed orders only
            $completedOrders = $vendorOrders->where('order_status', 'completed');
            $revenue = 0;
            foreach ($completedOrders as $order) {
                foreach ($order->orderItems as $item) {
                    if ($vendorItems->contains($item->item_id)) {
                        $revenue += ($item->order_item_price ?? 0) * ($item->order_item_quantity ?? 1);
                    }
                }
            }

            $productsCount = $vendor->items()->count();

            // Store Rating: average rating from all vendor's items
            $storeRating = \App\Models\Review::whereHas('item', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id ?? 0);
            })->avg('review_rating') ?? 0;
            $storeRating = round($storeRating, 1);

            // Recent Ads (3 most recent)
            $recentAds = \App\Models\Ad::with('item')
                ->whereHas('item', function($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id);
                })
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        } else {
            $ordersCount = 0;
            $recentOrders = collect();
            $revenue = 0;
            $productsCount = 0;
            $storeRating = 0;
            $recentAds = collect();
        }

        return view('vendor.dashboard', compact('recentProducts', 'ordersCount', 'recentOrders', 'revenue', 'productsCount', 'storeRating', 'recentAds'));
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
            'rental_duration_value' => 'nullable|integer|min:1',
            'rental_duration_unit' => 'nullable|string|in:day,week,month',
            'images' => 'nullable|array',
            'images.*' => 'image|max:4096',
        ]);
        $data['vendor_id'] = $vendor->id ?? null;

        $item = \App\Models\Item::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $file->move(public_path('images/products'), $filename);
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
        })->with(['user', 'orderItems.item'])->orderBy('created_at', 'desc')->paginate(25);
        return view('vendor.orders-list', compact('orders'));
    })->name('vendor.orders.list');

    // Vendor: Update order status
    Route::patch('/vendor/orders/{order}/status', function (Request $request, \App\Models\Order $order) {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        
        // Verify this order belongs to vendor's items
        $hasVendorItem = $order->orderItems()->whereHas('item', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id ?? 0);
        })->exists();
        
        if (!$hasVendorItem) abort(403);
        
        $order->update(['order_status' => $request->input('status')]);
        return redirect()->route('vendor.orders.list')->with('success', 'Order status updated');
    })->name('vendor.orders.updateStatus');

    // Vendor Ads Management
    Route::get('/vendor/ads', function () {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        $ads = \App\Models\Ad::whereHas('item', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id ?? 0);
        })->with(['item', 'payment'])->orderBy('created_at', 'desc')->paginate(25);
        return view('vendor.ads', compact('ads'));
    })->name('vendor.ads.index');

    Route::get('/vendor/ads/create', function () {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        $items = $vendor ? $vendor->items()->get() : collect();
        return view('vendor.ads-create', compact('items'));
    })->name('vendor.ads.create');

    // Debug: show pending_ad session for current vendor (auth only)
    Route::get('/debug/pending-ad', function () {
        if (!auth()->check()) abort(403);
        $pending = session('pending_ad');
        return response()->json([
            'user_id' => auth()->id(),
            'pending_ad' => $pending,
        ]);
    })->middleware('auth')->name('debug.pending-ad');

    // Vendor pays for ad (before creating) - compute price automatically and create Midtrans Snap
    Route::post('/vendor/ads/pay', function (Request $request) {
        if (auth()->user()->role !== 'vendor') abort(403);
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'ad_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $vendor = auth()->user()->vendor;
        $item = \App\Models\Item::find($validated['item_id']);
        if (!$vendor || $item->vendor_id !== $vendor->id) abort(403);

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('ad_image')) {
            $image = $request->file('ad_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/ads'), $imageName);
        }

        // Calculate days and price: 50,000/day (inclusive of both start and end)
        $start = \Carbon\Carbon::parse($validated['start_date'])->startOfDay();
        $end = \Carbon\Carbon::parse($validated['end_date'])->endOfDay();
        $days = max(1, $start->diffInDays($end) + 1);
        $pricePerDay = 50000;
        $totalPrice = $days * $pricePerDay;

        // Create ad payment record (payment_method will be set after Midtrans callback)
        $midtransOrderId = 'AD-' . time() . '-' . $vendor->id;
        $payment = \App\Models\Payment::create([
            'order_id' => null, // Not related to order
            'midtrans_order_id' => $midtransOrderId,
            'payment_method' => 'midtrans',
            'payment_type' => 'ad',
            'payment_total_amount' => $totalPrice,
            'payment_status' => 'pending',
        ]);

        // Create the Ad record immediately with status 'pending' so webhook can activate it
        $ad = \App\Models\Ad::create([
            'item_id' => $validated['item_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'price' => $totalPrice,
            'ad_image' => $imageName,
            'status' => 'pending',
            'payment_id' => $payment->id,
        ]);

        // Build Midtrans Snap payload
        $transactionDetails = [
            'order_id' => $midtransOrderId,
            'gross_amount' => (int) $totalPrice,
        ];

        $itemDetails = [[
            'id' => 'AD-' . $item->id,
            'price' => (int) $totalPrice,
            'quantity' => 1,
            'name' => 'Advertisement for: ' . substr($item->item_name ?? 'Item', 0, 50),
        ]];

        $customerDetails = [
            'first_name' => auth()->user()->name ?? 'Vendor',
            'email' => auth()->user()->email ?? 'vendor@example.com',
            'phone' => auth()->user()->phone ?? '081234567890',
        ];

        $params = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'callbacks' => [
                'finish' => route('payment.success'),
                'unfinish' => route('payment.pending'),
                'error' => route('payment.error'),
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Redirect to payment page for this ad (payment/midtrans handled there)
            return redirect()->route('vendor.ads.payment', $payment->id);

        } catch (\Exception $e) {
            // Fallback: still create ad record and redirect to payment view (without snap token)
            $ad = \App\Models\Ad::create([
                'item_id' => $validated['item_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'price' => $totalPrice,
                'ad_image' => $imageName,
                'status' => 'pending',
                'payment_id' => $payment->id,
            ]);

            return redirect()->route('vendor.ads.payment', $payment->id)->with('error', 'Failed to initialize Midtrans payment: ' . $e->getMessage());
        }
    })->name('vendor.ads.pay');

    // Show payment page
    Route::get('/vendor/ads/payment/{payment}', function (\App\Models\Payment $payment) {
        if (auth()->user()->role !== 'vendor') abort(403);
        // Load ad that references this payment
        $ad = \App\Models\Ad::where('payment_id', $payment->id)->first();
        if (!$ad) {
            return redirect()->route('vendor.ads.index')->with('error', 'Ad not found for this payment');
        }

        // Build Midtrans parameters similar to item payment flow
        $midtransOrderId = $payment->midtrans_order_id ?? ('AD-' . $payment->id . '-' . time());

        $transactionDetails = [
            'order_id' => $midtransOrderId,
            'gross_amount' => (int) $payment->payment_total_amount,
        ];

        $itemDetails = [[
            'id' => 'AD-' . ($ad->item_id ?? '0'),
            'price' => (int) $payment->payment_total_amount,
            'quantity' => 1,
            'name' => 'Advertisement for: ' . substr(\App\Models\Item::find($ad->item_id)->item_name ?? 'Item', 0, 50),
        ]];

        $customerDetails = [
            'first_name' => auth()->user()->name ?? 'Vendor',
            'email' => auth()->user()->email ?? 'vendor@example.com',
            'phone' => auth()->user()->phone ?? '081234567890',
        ];

        $params = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'callbacks' => [
                'finish' => route('payment.success'),
                'unfinish' => route('payment.pending'),
                'error' => route('payment.error'),
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Ensure payment record has midtrans_order_id so webhook can match
            if (!$payment->midtrans_order_id) {
                $payment->midtrans_order_id = $midtransOrderId;
                $payment->save();
            }

            return view('vendor.ads-payment', compact('payment', 'ad', 'snapToken'));
        } catch (\Exception $e) {
            // Fallback to previous behavior (use session snap token if available)
            return view('vendor.ads-payment', compact('payment', 'ad'))
                ->with('error', 'Failed to initialize Midtrans payment: ' . $e->getMessage());
        }
    })->name('vendor.ads.payment');

    // Note: Demo/manual ad-confirm route removed. Ad activation is handled exclusively by the Midtrans webhook.

    Route::get('/vendor/ads/{ad}/edit', function (\App\Models\Ad $ad) {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        if (!$vendor || $ad->item->vendor_id !== $vendor->id) abort(403);
        $items = $vendor->items()->get();
        return view('vendor.ads-edit', compact('ad', 'items'));
    })->name('vendor.ads.edit');

    Route::patch('/vendor/ads/{ad}', function (Request $request, \App\Models\Ad $ad) {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        if (!$vendor || $ad->item->vendor_id !== $vendor->id) abort(403);
        
        // Only allow updating the ad image from this form. Other fields are read-only.
        $validated = $request->validate([
            'ad_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('ad_image')) {
            // Delete old image if exists and not placeholder
            if ($ad->ad_image && $ad->ad_image !== 'ad_placeholder.jpg' && file_exists(public_path('images/ads/' . $ad->ad_image))) {
                @unlink(public_path('images/ads/' . $ad->ad_image));
            }

            $image = $request->file('ad_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/ads'), $imageName);
            $ad->update(['ad_image' => $imageName]);
            return redirect()->route('vendor.ads.index')->with('success', 'Ad image updated');
        }

        return redirect()->route('vendor.ads.index')->with('info', 'No changes made. Only ad image can be updated here.');
    })->name('vendor.ads.update');

    Route::delete('/vendor/ads/{ad}', function (\App\Models\Ad $ad) {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        if (!$vendor || $ad->item->vendor_id !== $vendor->id) abort(403);
        $ad->delete();
        return redirect()->route('vendor.ads.index')->with('success', 'Ad deleted');
    })->name('vendor.ads.destroy');

    // Edit vendor product
    Route::get('/vendor/products/{item}/edit', function (\App\Models\Item $item) {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        if (!$vendor || $item->vendor_id !== $vendor->id) abort(403);
        $item->load('galleries'); // Load gallery images
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
            'rental_duration_value' => 'nullable|integer|min:1',
            'rental_duration_unit' => 'nullable|string|in:day,week,month',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload: store to public/images/products and create gallery record
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\-_.]/', '_', $file->getClientOriginalName());
            $dest = public_path('images/products');
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
        $item->update(collect($data)->only(['item_name','item_description','item_price','item_type','item_status','item_stock','rental_duration_value','rental_duration_unit'])->toArray());
        return redirect()->route('vendor.products.list')->with('success', 'Product updated');
    })->name('vendor.products.update');

    // Delete gallery image
    Route::delete('/vendor/gallery/{gallery}', function (\App\Models\ItemGallery $gallery) {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;
        if (!$vendor || !$gallery->item || $gallery->item->vendor_id !== $vendor->id) abort(403);

        // Delete the actual file if it exists
        $paths = [
            public_path('images/products/' . $gallery->image_path),
            public_path('images/items/' . $gallery->image_path),
            public_path('images/item/' . $gallery->image_path),
        ];
        foreach ($paths as $path) {
            if (file_exists($path)) {
                @unlink($path);
                break;
            }
        }

        $gallery->delete();
        return redirect()->back()->with('success', 'Image deleted successfully');
    })->name('vendor.gallery.destroy');
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