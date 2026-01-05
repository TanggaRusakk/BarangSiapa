<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\RentalInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    /**
     * Calculate rental units based on duration and item rental settings
     */
    public function calculateRentalUnits(Item $item, string $startDate, string $endDate): int
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $durationDays = max(1, $start->diff($end)->days);
        
        $rentalDurationValue = $item->rental_duration_value ?? 1;
        $rentalDurationUnit = $item->rental_duration_unit ?? 'day';
        
        $rentalUnits = 1;
        
        switch ($rentalDurationUnit) {
            case 'day':
                $rentalUnits = ceil($durationDays / $rentalDurationValue);
                break;
            case 'week':
                $rentalUnits = ceil($durationDays / ($rentalDurationValue * 7));
                break;
            case 'month':
                $rentalUnits = ceil($durationDays / ($rentalDurationValue * 30));
                break;
        }
        
        return max(1, $rentalUnits);
    }

    /**
     * Calculate order totals
     */
    public function calculateOrderTotals(Item $item, int $quantity, int $rentalUnits = 1): array
    {
        $itemPricePerUnit = $item->item_price * $rentalUnits;
        $itemTotal = $itemPricePerUnit * $quantity;
        $serviceFee = $itemTotal * 0.05; // 5% service fee
        $totalAmount = $itemTotal + $serviceFee;
        
        return [
            'item_price_per_unit' => $itemPricePerUnit,
            'item_total' => $itemTotal,
            'service_fee' => $serviceFee,
            'total_amount' => $totalAmount,
        ];
    }

    /**
     * Validate if user can create order
     */
    public function canUserCreateOrder(string $userRole): bool
    {
        return !in_array($userRole, ['admin', 'vendor']);
    }

    /**
     * Validate if item is available for order
     */
    public function isItemAvailable(Item $item, int $quantity): array
    {
        if ($item->item_status !== 'available') {
            return ['valid' => false, 'message' => 'Item tidak tersedia untuk dipesan.'];
        }

        if ($item->item_stock <= 0) {
            return ['valid' => false, 'message' => 'Item tidak tersedia untuk dipesan.'];
        }

        if ($item->item_stock < $quantity) {
            return ['valid' => false, 'message' => 'Stok tidak mencukupi!'];
        }

        return ['valid' => true];
    }

    /**
     * Create a new order with all related data
     */
    public function createOrder(array $data): Order
    {
        $item = Item::findOrFail($data['item_id']);
        $isRent = $item->item_type === 'rent';

        // Validate rental dates for rent items
        if ($isRent && (!isset($data['rental_start_date']) || !isset($data['rental_end_date']))) {
            throw new \Exception('Tanggal rental harus diisi untuk item rent!');
        }

        DB::beginTransaction();
        try {
            // Calculate rental units if applicable
            $rentalUnits = 1;
            $durationDays = 0;
            
            if ($isRent) {
                $rentalUnits = $this->calculateRentalUnits(
                    $item,
                    $data['rental_start_date'],
                    $data['rental_end_date']
                );
                
                $start = new \DateTime($data['rental_start_date']);
                $end = new \DateTime($data['rental_end_date']);
                $durationDays = max(1, $start->diff($end)->days);
            }
            
            // Calculate totals
            $totals = $this->calculateOrderTotals($item, $data['quantity'], $rentalUnits);
            
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totals['total_amount'],
                'order_type' => $isRent ? 'rent' : 'buy',
                'order_status' => 'pending',
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item->id,
                'order_item_quantity' => $data['quantity'],
                'order_item_price' => $totals['item_price_per_unit'],
                'order_item_subtotal' => $totals['item_total'],
            ]);

            // Create rental info if applicable
            if ($isRent) {
                RentalInfo::create([
                    'order_id' => $order->id,
                    'start_date' => $data['rental_start_date'],
                    'end_date' => $data['rental_end_date'],
                    'duration_days' => $durationDays,
                ]);
            }

            DB::commit();
            
            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
