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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->string('transaction_type')->default('cash');
            $table->integer('reference_number');
            $table->json('notes')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('stock_orders')->onDelete('cascade');
            $table->foreign('reference_number')->references('tracking_number')->on('stock_deliveries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};