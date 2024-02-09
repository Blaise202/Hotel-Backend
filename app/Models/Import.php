<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['quantity' , 'import_date'];

    public function StockItem(){
        return $this->belongsTo(StockItem::class);
    }
}