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
        Schema::table('orders', function (Blueprint $table) {
            // Add order_total_amount as alias for total_amount for compatibility
            if (!Schema::hasColumn('orders', 'order_total_amount')) {
                $table->decimal('order_total_amount', 15, 2)->nullable()->after('total_amount');
            }
        });
        
        // Copy existing total_amount values to order_total_amount
        \DB::statement('UPDATE orders SET order_total_amount = total_amount WHERE order_total_amount IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'order_total_amount')) {
                $table->dropColumn('order_total_amount');
            }
        });
    }
};
