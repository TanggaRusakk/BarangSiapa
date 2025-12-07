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
use App\Http\Controllers\OrderServiceFeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdController::class, 'index']);

// Public API-like endpoints (simple index actions)
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/galleries', [ItemGalleryController::class, 'index'])->name('galleries.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');
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
    $recentProducts = \App\Models\Item::orderBy('created_at', 'desc')->take(6)->get();
    $lastViewed = null;
    if (session('last_viewed_item')) {
        $lastViewed = \App\Models\Item::find(session('last_viewed_item'));
    }
    return view('dashboard', compact('recentProducts', 'lastViewed'));
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
});

require __DIR__.'/auth.php';