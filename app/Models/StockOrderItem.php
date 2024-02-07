<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'item_id'
    ];
    
    public function StockOrder(){
        return $this->belongsTo(StockOrder::class);
    }
    
    public function StockOrderItem(){
        return $this->belongsTo(StockItem::class);
    }
}