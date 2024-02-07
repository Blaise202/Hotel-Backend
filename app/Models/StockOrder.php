<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOrder extends Model
{
    use HasFactory;

    public function StockOrderItem(){
        return $this->hasOne(StockOrderItem::class);
    }

    public function StockDelivery(){
        return $this->hasOne(StockDelivery::class);
    }
}