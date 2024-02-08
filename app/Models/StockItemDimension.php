<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItemDimension extends Model
{
    use HasFactory, HasUuids;

    public function stockItem(){
        return $this->belongsToMany(StockItem::class);
    }
}