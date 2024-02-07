<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockWasteDisposal extends Model
{
    use HasFactory;

    public function stockItem(){
        return $this->hasMany(StockItem::class);
    }
}