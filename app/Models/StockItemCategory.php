<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItemCategory extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'description'
    ];

    public function StockItem(){
        return $this->hasMany(StockItem::class);
    }
}