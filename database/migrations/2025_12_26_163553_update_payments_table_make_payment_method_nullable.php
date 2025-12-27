<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Change payment_method to string nullable to support any Midtrans payment methods
            $table->string('payment_method')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revert back to enum (if needed)
            $table->enum('payment_method', [
                "credit_card",
                "bank_transfer",
                "echannel",
                "permata",
                "bca_klikpay",
                "cimb_clicks",
                "danamon_online",
                "bri_epay",
                "gopay",
                "shopeepay",
                "qris",
                "indomaret",
                "alfamart",
                "kredivo",
                "akulaku",
                "midtrans"
            ])->change();
        });
    }
};
