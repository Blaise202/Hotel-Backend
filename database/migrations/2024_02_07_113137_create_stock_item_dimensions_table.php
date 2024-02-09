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
        Schema::create('stock_item_dimensions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('stock_item_id');
            $table->string('furniture')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('volume')->nullable();
            $table->timestamps();

            $table->foreign('stock_item_id')->references('id')->on('stock_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_item_dimensions');
    }
};