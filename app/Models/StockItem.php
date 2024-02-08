<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'unit_price',
        'category_id',
        'supplier_id'
    ];
    
    public function ItemCategory(){
        return $this->hasOne(StockItemCategory::class);
    }

    public function ItemQuantity(){
        return $this->hasOne(StockItemQuantity::class);
    }

    public function StockOrderItem(){
        return $this->hasOne(StockOrderItem::class);
    }

    public function StockTransaction(){
        return $this->hasMany(StockTransaction::class);
    }

    public function StockWasteDisposal(){
        return $this->hasMany(StockWasteDisposal::class);
    }

    public function ItemDimension(){
        return $this->hasOne(StockItemDimension::class);
    }
}