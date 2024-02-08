<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockWasteDisposal extends Model
{
    use HasFactory, HasUuids;

    public function stockItem(){
        return $this->hasMany(StockItem::class);
    }
}