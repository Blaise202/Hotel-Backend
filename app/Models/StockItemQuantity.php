<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItemQuantity extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'quantity'
    ];

    public function StockItem(){
        return $this->belongsTo(StockItem::class);
    }

    public function StockOrder(){
        return $this->belongsToMany(StockOrder::class);
    }
}