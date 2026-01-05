<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Item;
use App\Models\Category;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;

class AdController extends Controller
{
    /**
     * Show the application landing page using Ads and Items.
     */
    public function index(Request $request)
    {
        // Popular items (top 5 most ordered)
        $popularItems = Item::where('item_status', 'available')
            ->with(['itemGalleries', 'vendor.user'])
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        // Mark expired ads as inactive so they won't show anymore
        Ad::where('status', 'active')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->update(['status' => 'inactive']);

        // Only show ads that are active, have a successful payment, and whose schedule includes now
        $finalPaymentStatuses = ['settlement', 'capture', 'success'];
        $ads = Ad::where('status', 'active')
            ->whereHas('payment', function ($q) use ($finalPaymentStatuses) {
                $q->whereIn('payment_status', $finalPaymentStatuses);
            })
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->with('item.itemGalleries')
            ->latest()
            ->take(6)
            ->get();

        $trending = $ads->map(function ($ad) {
            return $ad->item;
        })->filter();

        // fallback to latest available items if not enough ads
        if ($trending->count() < 6) {
            $more = Item::where('item_status', 'available')
                ->with('itemGalleries')
                ->latest()
                ->take(6 - $trending->count())
                ->get();
            $trending = $trending->merge($more);
        }

        // items: for product grid (use paginator so views can call ->links())
        $items = Item::where('item_status', 'available')
            ->with('itemGalleries')
            ->latest()
            ->paginate(12);

        $categories = Category::all();

        return view('home', compact('trending', 'items', 'categories', 'popularItems', 'ads'));
    }

    /**
     * Handle vendor ad creation and payment initialization.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'vendor') abort(403);

        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'ad_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $vendor = auth()->user()->vendor;
        $item = Item::find($validated['item_id']);
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

        // Create ad payment record
        $midtransOrderId = 'AD-' . time() . '-' . $vendor->id;
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'order_id' => null,
            'midtrans_order_id' => $midtransOrderId,
            'payment_method' => 'midtrans',
            'payment_type' => 'ad',
            'payment_total_amount' => $totalPrice,
            'payment_status' => 'pending',
        ]);

        // Create the Ad record immediately with status 'pending'
        $ad = Ad::create([
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
            // Ensure Midtrans SDK is configured (server/client keys and flags)
            MidtransConfig::$serverKey = config('midtrans.server_key');
            MidtransConfig::$isProduction = config('midtrans.is_production');
            MidtransConfig::$isSanitized = config('midtrans.is_sanitized');
            MidtransConfig::$is3ds = config('midtrans.is_3ds');

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            session(['ad_snap_token_' . $payment->id => $snapToken]);
            return redirect()->route('vendor.ads.payment', $payment->id);
        } catch (\Exception $e) {
            Log::error('Failed to create Midtrans snap token for ad payment', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);

            return redirect()->route('vendor.ads.create')
                ->with('error', 'Failed to initialize payment gateway: ' . $e->getMessage());
        }
    }
}
