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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->string('item_name');
            $table->text('item_description')->nullable();
            $table->enum('item_type', ['buy', 'rent']);
            $table->integer('item_price');
            $table->enum('item_status', ['available', 'unavailable']);
            $table->integer('rental_duration_unit')->nullable();
            $table->integer('rental_duration_value')->nullable();
            $table->integer('item_stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
