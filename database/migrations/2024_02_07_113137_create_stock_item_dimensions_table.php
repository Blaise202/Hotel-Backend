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
            $table->char('id')->primary()->default(\Illuminate\Support\Facades\DB::raw('uuid_generate_v4()'));
            $table->char('item_id');
            $table->string('furniture')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('volume')->nullable();
            $table->timestamps();

            $table->foreign('item_id')->references('stock_items')->on('id')->onDelete('cascade');
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