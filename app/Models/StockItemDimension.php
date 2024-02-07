<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItemDimension extends Model
{
    use HasFactory;

    public function stockItem(){
        return $this->belongsToMany(StockItem::class);
    }
}