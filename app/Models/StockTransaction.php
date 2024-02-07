<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    public function StockItem(){
        return $this->hasMany(StockItem::class);
    }

    public function StockDelivery(){
        return $this->hasone(StockDelivery::class);
    }
}