<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_deliveries', function (Blueprint $table) {
            $table->char('id')->primary();
            $table->char('order_id');
            $table->dateTime('delivery_date')->default(Carbon::now());
            $table->string('received_by')->nullable();
            $table->string('delivery_status')->default('ongoing');
            $table->string('shipping_company')->nullable();
            $table->integer('tracking_number')->default(rand(100000,900000000))->unique();
            $table->timestamps();

            $table->foreign('order_id')->references('stock_orders')->on('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_deliveries');
    }
};