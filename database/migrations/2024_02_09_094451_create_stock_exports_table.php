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
        Schema::create('stock_exports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('stock_item_id');
            $table->integer('quantity');
            // $table->string('received_by');
            $table->date('export_date');
            $table->timestamps();

            $table->foreign('stock_item_id')->references('id')->on('stock_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_exports');
    }
};