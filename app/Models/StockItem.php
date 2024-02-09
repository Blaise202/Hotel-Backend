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
        'supplier_id',
        'item_images'
    ];
    protected $casts = ['item_images' => 'array'];

    public function setFileNameAttribute($value){
        $this->attributes['item_images'] = json_encode($value);
    }
    
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

    public function Imports(){
        return $this->hasMany(Import::class);
    }

    public function StockExport(){
        return $this->hasMany(StockExport::class);
    }

    public function Requisition(){
        return $this->hasMany(Requisition::class);
    }
}