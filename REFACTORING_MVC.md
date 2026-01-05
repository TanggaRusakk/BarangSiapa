# Refactoring MVC - Dokumentasi Perubahan

## Ringkasan
Aplikasi TemanPerantara telah direfactor untuk menerapkan arsitektur MVC (Model-View-Controller) yang benar dengan menambahkan Service Layer.

## Perubahan Struktur

### 1. **Service Layer Baru** 
Dibuat folder `app/Services/` yang berisi business logic:

#### `OrderService.php`
**Tanggung Jawab:**
- Validasi order (user role, stock availability)
- Kalkulasi rental units dan totals
- Logic pembuatan order dengan transaction

**Methods:**
- `calculateRentalUnits()` - Menghitung rental units berdasarkan durasi
- `calculateOrderTotals()` - Menghitung total harga + service fee
- `canUserCreateOrder()` - Validasi role user
- `isItemAvailable()` - Validasi ketersediaan item
- `createOrder()` - Create order dengan semua data terkait

#### `PaymentService.php`
**Tanggung Jawab:**
- Payment processing logic
- Midtrans integration
- Payment status management

**Methods:**
- `isPaymentFinalized()` - Check payment status
- `determinePaymentDetails()` - Tentukan tipe dan jumlah payment
- `buildItemDetails()` - Build item details untuk Midtrans
- `generateSnapToken()` - Generate Midtrans snap token
- `createOrUpdatePayment()` - Create/update payment record
- `markPaymentAsSettled()` - Mark payment sebagai settled
- `markPaymentAsFailed()` - Mark payment sebagai failed
- `validateWebhookAmount()` - Validasi amount dari webhook

### 2. **Controller Refactoring**

#### `OrderController.php`
**Sebelum:** Controller mengandung semua business logic (perhitungan rental, validasi, kalkulasi harga)

**Sesudah:** Controller hanya menangani HTTP request/response, memanggil OrderService untuk business logic

**Perubahan:**
```php
// Sebelum (di controller)
$rentalUnits = ceil($durationDays / $rentalDurationValue);
$itemTotal = $itemPricePerUnit * $request->quantity;
// ... logic kompleks lainnya

// Sesudah (delegasi ke service)
$order = $this->orderService->createOrder([...]);
```

#### `PaymentController.php`
**Sebelum:** Controller mengandung logic kompleks untuk payment processing, Midtrans integration, webhook handling

**Sesudah:** Controller mendelegasikan business logic ke PaymentService

**Perubahan:**
```php
// Sebelum (di controller)
$snapToken = Snap::getSnapToken($params);
$itemDetails = array_map(function($item) {...}, $itemDetails);

// Sesudah (delegasi ke service)
$snapToken = $this->paymentService->generateSnapToken($order, $paymentDetails);
```

### 3. **Model Enhancement**

#### `Order.php`
**Methods Baru:**
- `isRental()` - Check apakah order adalah rental
- `isPaid()` - Check apakah order sudah dibayar
- `isPending()` - Check apakah order pending
- `calculateTotal()` - Calculate total dari order items

#### `Item.php`
**Methods Baru:**
- `isAvailable()` - Check ketersediaan item
- `hasStock()` - Check stock availability
- `isRental()` - Check apakah item adalah rental

## Prinsip MVC yang Diterapkan

### **Model**
- ✅ Menangani data dan business logic domain
- ✅ Accessor methods untuk data manipulation
- ✅ Helper methods untuk domain logic (isAvailable, isPaid, dll)

### **View**
- ✅ Hanya menampilkan data
- ✅ Tidak ada business logic di views

### **Controller**
- ✅ Hanya menangani HTTP request/response
- ✅ Validasi input
- ✅ Delegasi business logic ke Service/Model
- ✅ Return response/view

### **Service Layer**
- ✅ Complex business logic
- ✅ Koordinasi antara multiple models
- ✅ Transaction management
- ✅ External API integration (Midtrans)

## Keuntungan Refactoring

1. **Separation of Concerns** - Setiap layer punya tanggung jawab yang jelas
2. **Reusability** - Service methods bisa digunakan di berbagai controller
3. **Testability** - Business logic di service lebih mudah di-test
4. **Maintainability** - Lebih mudah maintain dan debug
5. **Scalability** - Mudah menambah fitur baru tanpa mengubah existing code

## Testing
Semua refactoring tidak mengubah behavior aplikasi, hanya mengubah struktur code.

## Next Steps
- Buat unit tests untuk Service layer
- Tambah Service untuk fitur-fitur lain (ReviewService, AdService, dll)
- Implement Repository pattern untuk data access layer
