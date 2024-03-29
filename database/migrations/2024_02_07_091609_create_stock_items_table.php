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
        Schema::create('stock_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->longText('description');
            $table->double('unit_price');
            $table->string('storage_location')->default('normal_stock');
            $table->uuid('category_id');
            $table->uuid('supplier_id');
            $table->string('status')->default('active');
            $table->string('item_images')->nullable();
            $table->string('manufacturer')->nullable();
            $table->dateTimeTz('expiry_date');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('stock_item_categories')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('stock_suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};