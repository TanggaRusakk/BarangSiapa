<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private const FINAL_PAYMENT_STATUSES = ['settlement', 'capture', 'success'];
    private const SUCCESSFUL_STATUSES = ['capture', 'settlement'];
    private const FAILED_STATUSES = ['deny', 'expire', 'cancel'];

    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Check if payment is already finalized
     */
    public function isPaymentFinalized(?Payment $payment): bool
    {
        return $payment && in_array($payment->payment_status, self::FINAL_PAYMENT_STATUSES);
    }

    /**
     * Determine payment type and amount
     */
    public function determinePaymentDetails(Order $order, ?string $requestOption, ?string $sessionOption, ?Payment $existingPayment): array
    {
        $orderTotal = $order->total_amount ?? $order->order_total_amount ?? $order->calculated_total;
        $isRental = $order->order_type === 'rent';

        // For buy orders, always use full payment
        if (!$isRental) {
            $paymentType = 'full';
            $paymentAmount = $orderTotal;
        } else {
            // For rental orders, determine based on user choice (default to dp)
            $chosenOption = $requestOption ?? $sessionOption ?? ($existingPayment->payment_type ?? null) ?? 'dp';
            $paymentType = $chosenOption;
            $paymentAmount = ($paymentType === 'dp') ? round($orderTotal * 0.30) : $orderTotal;
        }

        return [
            'payment_type' => $paymentType,
            'payment_amount' => $paymentAmount,
            'order_total' => $orderTotal,
            'is_rental' => $isRental,
        ];
    }

    /**
     * Build item details for Midtrans
     */
    public function buildItemDetails($orderItems, string $paymentType = 'full'): array
    {
        $itemDetails = [];
        
        foreach ($orderItems as $orderItem) {
            $price = (int) ($orderItem->order_item_price ?? $orderItem->price ?? 0);
            $quantity = (int) ($orderItem->order_item_quantity ?? $orderItem->quantity ?? 1);
            
            if ($paymentType === 'dp') {
                $price = round($price * 0.30);
            }
            
            $itemDetails[] = [
                'id' => $orderItem->item->id ?? 'ITEM-' . $orderItem->id,
                'price' => $price,
                'quantity' => $quantity,
                'name' => substr($orderItem->item->item_name ?? 'Item', 0, 50),
            ];
        }
        
        return $itemDetails;
    }

    /**
     * Add service fee to item details if needed
     */
    public function addServiceFeeToItemDetails(array $itemDetails, int $paymentAmount, bool $isRental): array
    {
        $itemTotal = collect($itemDetails)->sum(fn($item) => $item['price'] * $item['quantity']);
        
        if ($itemTotal < $paymentAmount) {
            $itemDetails[] = [
                'id' => 'SERVICE_FEE',
                'price' => (int) ($paymentAmount - $itemTotal),
                'quantity' => 1,
                'name' => $isRental ? 'DP Service Fee' : 'Service Fee',
            ];
        }
        
        return $itemDetails;
    }

    /**
     * Generate Midtrans snap token
     */
    public function generateSnapToken(Order $order, array $paymentDetails): string
    {
        $midtransOrderId = 'ORDER-' . $order->id . '-' . time();

        $transactionDetails = [
            'order_id' => $midtransOrderId,
            'gross_amount' => (int) $paymentDetails['payment_amount'],
        ];

        $itemDetails = $this->buildItemDetails($order->orderItems, $paymentDetails['payment_type']);
        $itemDetails = $this->addServiceFeeToItemDetails(
            $itemDetails,
            $paymentDetails['payment_amount'],
            $paymentDetails['is_rental']
        );

        $customerDetails = [
            'first_name' => $order->user->name ?? 'Customer',
            'email' => $order->user->email ?? 'customer@example.com',
            'phone' => $order->user->phone ?? '081234567890',
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

        return Snap::getSnapToken($params);
    }

    /**
     * Create or update payment record
     */
    public function createOrUpdatePayment(Order $order, string $midtransOrderId, array $paymentDetails): Payment
    {
        return Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => $order->user_id,
                'midtrans_order_id' => $midtransOrderId,
                'payment_method' => 'midtrans',
                'payment_type' => $paymentDetails['payment_type'],
                'payment_total_amount' => $paymentDetails['payment_amount'],
                'payment_status' => 'pending',
            ]
        );
    }

    /**
     * Mark payment as settled
     */
    public function markPaymentAsSettled(Payment $payment, Order $order, string $paymentType): void
    {
        DB::transaction(function () use ($payment, $order, $paymentType) {
            $payment->update([
                'payment_status' => 'settlement',
                'payment_method' => $paymentType,
                'paid_at' => now(),
            ]);

            $order->update(['order_status' => 'paid']);
        });

        Log::info('Payment successfully settled', [
            'order_id' => $order->id,
            'payment_method' => $paymentType,
        ]);
    }

    /**
     * Mark payment as failed
     */
    public function markPaymentAsFailed(Payment $payment, Order $order, string $status): void
    {
        $payment->update(['payment_status' => $status]);
        $order->update(['order_status' => 'cancelled']);
        
        Log::info('Payment failed and order cancelled', [
            'order_id' => $order->id,
            'reason' => $status,
        ]);
    }

    /**
     * Validate webhook amount
     */
    public function validateWebhookAmount(Payment $payment, int $grossAmount): bool
    {
        return (int)$grossAmount === (int)$payment->payment_total_amount;
    }
}
