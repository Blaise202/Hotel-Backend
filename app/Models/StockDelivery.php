<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDelivery extends Model
{
    use HasFactory;

    public function StockOrder(){
        return $this->belongsTo(StockOrder::class);
    }

    public function StockTransaction(){
        return $this->belongsTo(StockTransaction::class);
    }
}