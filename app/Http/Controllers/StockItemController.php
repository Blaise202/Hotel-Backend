<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use App\Models\StockItemDimension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockItemController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'description' => 'required',
            'unit_price' => 'required|numeric',
            'storage' => 'sometimes|string',
            'category_id' => 'required|uuid|exists:stock_item_categories,id',
            'supplier_id' => 'required|uuid|exists:stock_suppliers,id',
            'weight' => 'sometimes|numeric',
            'furniturer' => 'sometimes|string',
            'length' => 'sometimes|numeric',
            'width' => 'sometimes|numeric',
            'height' => 'sometimes|numeric',
            'volume' => 'sometimes|numeric',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        if ($request->has(['furniture', 'length', 'width', 'height', 'volume'])) {
            StockItemDimension::create([
                'furniture' => $request->furniture,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'volume' => $request->volume,
            ]);
        }
        
        StockItem::create($request->all());
        
        return response()->json(['message' => 'Data stored successfully']);
    }
    public function showitems()
    {
        $itemss = StockItem::all();
        $count = $itemss->count();
        if($count == 0){
            return response()->json('No Item items Found');
        }
        return response()->json(['itemss Found'=>$itemss]);
    }

}