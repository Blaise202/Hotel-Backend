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
        Schema::create('stock_orders', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('supplier_id');
            $table->dateTime('order_date')->default(Carbon::now());
            $table->dateTime('expected_delivery_date');
            $table->string('order_status')->default('unsettled');
            $table->integer('total_amount');
            $table->string('delivery_method')->nullable();
            $table->json('payment_terms')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('stock_suppliers')->on('id')->ondelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_orders');
    }
};