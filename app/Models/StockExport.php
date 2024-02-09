<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockExport extends Model
{
    use HasFactory, HasUuids ;

    protected $fillable = ['quantity', 'export_date'];

    public function StockItem(){
        return $this->belongsTo(StockItem::class);
    }
}