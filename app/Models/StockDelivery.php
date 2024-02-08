<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDelivery extends Model
{
    use HasFactory, HasUuids;

    public function StockOrder(){
        return $this->belongsTo(StockOrder::class);
    }

    public function StockTransaction(){
        return $this->belongsTo(StockTransaction::class);
    }
}