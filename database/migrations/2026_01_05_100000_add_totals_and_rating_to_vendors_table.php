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
        Schema::table('vendors', function (Blueprint $table) {
            $table->unsignedBigInteger('total_revenue')->default(0)->after('description');
            $table->unsignedInteger('total_orders')->default(0)->after('total_revenue');
            $table->decimal('rating', 3, 1)->default(0)->after('total_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['total_revenue', 'total_orders', 'rating']);
        });
    }
};
