<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSupplier extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name','contact','phone','email','image'];

    public function stockItem(){
        return $this->hasMany(StockItem::class);
    }

    public function supplierAddress(){
        return $this->hasOne(SupplierAddress::class);
    }
}