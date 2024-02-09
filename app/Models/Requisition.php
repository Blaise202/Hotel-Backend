<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory, HasUuids;
    
    protected $fillable = ['quantity','expected_deadline'];

    public function StockItem(){
        return $this->belongsTo(StockItem::class);
    }
}