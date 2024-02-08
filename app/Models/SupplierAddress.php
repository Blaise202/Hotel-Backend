<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAddress extends Model
{
    use HasFactory, HasUuids;

    protected $fillable= ['stock_supplier_id','city','state','zipcode','country','address'];

    public function supplier(){
        return $this->belongsTo(StockSupplier::class);
    }

}