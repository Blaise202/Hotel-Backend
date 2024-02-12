<?php

namespace App\Http\Controllers;

use App\Http\Traits\NotificationTrait;
use App\Models\Import;
use App\Models\StockItem;
use App\Models\StockItemDimension;
use App\Models\StockItemQuantity;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockItemController extends Controller
{

    use NotificationTrait;

    public function showitems()
    {
        $items = StockItem::where('status','active')->get();
        $count = $items->count();
        if($count == 0){
            return response()->json('No Item items Found');
        }
        return response()->json([
            'message' => 'success',
            'items Found'=>$items,
            'alerts' => $this->stockLevelAlerts() ?: 0,
            'status' => 200
        ]);
    }

    public function ShowOneItem($id)
    {
        $item = StockItem::where('id', $id)->get();
        if(!$item){
            return response()->json([
                'message' => 'error',
                'response' => 'product Not Found',
                'status' => 404
            ]);
        }
        $itemQuantity = StockItemQuantity::where('stock_item_id', $id)->first()->quantity;
        return response()->json([
            'message' => 'success',
            'response' => $item,
            'quantity left' => $itemQuantity,
            'status' => 200
        ]);
    }
    
    public function store(Request $request)
    {
        $item = new StockItem();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:250|unique:stock_items,name',
            'description' => 'required',
            'unit_price' => 'required|numeric',
            'storage' => 'sometimes|string',
            'category_id' => 'required|uuid|exists:stock_item_categories,id',
            'supplier_id' => 'required|uuid|exists:stock_suppliers,id',
            'expiry_date'=>'required|date',
            'quantity' => 'required|numeric',
            'weight' => 'sometimes|string',
            'furniture' => 'sometimes',
            'length' => 'sometimes|string',
            'width' => 'sometimes|string',
            'height' => 'sometimes|string',
            'volume' => 'sometimes|string',
            'item_images.*' => 'required|image|mimes:jpeg,png,jpg.avif'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'error',
                'response' => $validator->errors(),
                'status' => 404
            ]);
        }

        $item->name = $request->name;
        $item->description = $request->description;
        $item->unit_price = $request->unit_price;
        $item->storage_location = $request->storage ? $request->storage : 'normal_storage';
        $item->category_id = $request->category_id;
        $item->supplier_id = $request->supplier_id;
        $item->expiry_date = $request->expiry_date;

        $files = [];
        foreach($request->file('item_images') as $file){
            $filename = $request->name.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move('stock_items', $filename);
            $files[] = $filename;
        }
        $item->item_images = $files;

        $item->save();

        $issues = [];
        
        $dimensions = $item->ItemDimension()->updateOrCreate([
            'furniture' => $request->furniture,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'volume' => $request->volume,
            'weight' => $request->weight,
        ]);
        if(!$dimensions){
            $result = 'Item Dimensions Were Not Saved';
            $issues[] = $result;
        }

        $quantity = $item->ItemQuantity()->updateOrCreate(
            [],
            ['quantity'=>DB::raw("quantity + $request->quantity")]
        );
        if(!$quantity){
            $result = 'Item Quantity Was Not Saved';
            $issues[] = $result;
        }
        $imp = $item->Imports()->create([
            'quantity' => $request->quantity,
            'import_date' => Carbon::now()
        ]);
        if(!$imp){
            $result = 'This Import Was Not Recorded';
            $issues[] = $result;
        }
        return response()->json([
            'message' => 'success',
            'response' => 'Data stored successfully',
            'data' => $item,
            'issues' => $issues ? $issues : 0,
            'status' => 200
        ]);
    }

    public function updateItem(Request $request, $id)
    {
        $item = StockItem::find($id);
        if(!$item){
            return response()->json([
                'message' => 'error',
                'response'=>'item  Not Found',
                'status' => 400
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:250|unique:stock_items,name',
            'description' => 'sometimes',
            'unit_price' => 'sometimes|numeric',
            'storage_location' => 'sometimes',
            'expiry_date'=>'sometimes|date',
            'status' => 'sometimes|string',
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'error',
                'response'=>$validator->errors(),
                'status' => 400
            ]);
        }
        $item->update($request->all());
        return response()->json([
            'message' => 'success',
            'response'=>$item,
            'status' =>200
        ]);
    }

    public function deleteItem($id)
    {
        $item = StockItem::find($id);
        if(!$item) {
            return response()->json([
                'message' => 'error',
                'response' => 'item not found',
                'status' => 404
            ]);
        }
        $item->status = 'deleted';
        $status = $item->save();
        $issues = [];
        if(!$status){
            $result = 'Item status Did Not Change';
            $issues[] = $result;
        }
    
        return response()->json([
            'message' => 'success',
            'response' => 'item deleted successfully',
            'issues' => $issues ? $issues : 0,
            'status' => 200
        ]);
    }

    public function destroyItem($id)
    {
        $item = StockItem::find($id);
        if (!$item) {
            return response()->json([
                'message' => 'error',
                'response' => 'Item Not Found',
                'status' => 500
            ]);
        }
        
        $issues = [];

        $itemDimensions = StockItemDimension::where('stock_item_id', $id)->first();
        if ($itemDimensions) {
            $deleteDims = $itemDimensions->delete();
            if (!$deleteDims) {
                $issues[] = 'Item Dimensions Were Not Deleted';
            }
        }

        $itemQuantity = StockItemQuantity::where('stock_item_id', $id)->first();
        if ($itemQuantity) {
            $deleteQuantity = $itemQuantity->delete();
            if (!$deleteQuantity) {
                $issues[] = 'Item Dimensions Were Not Deleted';
            }
        }


        $itemImportsHistory = Import::where('stock_item_id', $id)->first();
        if ($itemImportsHistory) {
            $deleteImports = $itemImportsHistory->delete();
            if (!$deleteImports) {
                $issues[] = 'Item Imports Were Not Deleted';
            }
        }

        $item->delete();

        return response()->json([
            'message' => 'success',
            'response' => 'Stock Item Deleted Successfully',
            'issues' => $issues ?: 0,
            'status' => 200
        ]);
    }
    
    public function updateItemQuantity(Request $request)
    {
        try {
            $data = $request->validate([
                'stock_item_id' => 'required|exists:stock_items,id',
                'quantity' => 'required|numeric'
            ]);
            $item_id = $data['stock_item_id'];
            $quantity = $data['quantity'];
            $item = StockItem::findOrFail($item_id);
            $item->ItemQuantity()->updateOrCreate(
                ['stock_item_id' => $item_id ],
                ['quantity' => DB::row("quantity + $quantity")]
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function importItem(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|numeric'
        ]);
        $quantity = $data['quantity'];
        $item = StockItem::findOrFail($id);
        if($quantity < 0){
            return response()->json([
                'message' => 'error',
                'response' => 'sorry the amount '. $quantity. ' is not allowed',
                'status' => 404
            ]);
        }
        $item->Imports()->create([
            'quantity' => $quantity,
            'import_date' => Carbon::now()
        ]);
        $item->ItemQuantity()->updateOrCreate(
            [],
            ['quantity'=> DB::raw("quantity + $quantity")]
        );

        $newQuantity = StockItemQuantity::where('stock_item_id', $id)->first()->quantity;

        return response()->json([
            'message' => 'success',
            'response' => 'product quantity updated successfully',
            'updated Quantity' => $newQuantity,
            'status' => 200
        ]);
    }

    public function exportItem(Request $request, $id)
    {
        try{
        $data = $request->validate([
            'quantity' => 'required|numeric'
        ]);
        $quantity = $data['quantity'];
        $item = StockItem::findOrFail($id);
        if($quantity < 0){
            return response()->json([
                'message' => 'error',
                'response' => 'sorry the amount '. $quantity. 'is not allowed',
                'status' => 404
            ]);
        }
        $initialQuantity = StockItemQuantity::where('stock_item_id', $id)->first()->quantity;
        if($quantity > $initialQuantity){
            return response()->json([
                'message' => 'error',
                'response' => 'the amount provided exceeds the available please enter less instead',
                'the amount available' => $initialQuantity,
                'status' => 404
            ]);
        }
        $item->StockExport()->create([
            'quantity' => $quantity,
            'export_date' => Carbon::now()
        ]);
        $item->ItemQuantity()->updateOrCreate(
            [],
            ['quantity'=> DB::raw("quantity - $quantity")]
        );
        
        $newQuantity = StockItemQuantity::where('stock_item_id', $id)->first()->quantity;

        return response()->json([
            'message' => 'success',
            'response' => 'product quantity updated successfully',
            'updated Quantity' => $newQuantity,
            'status' => 200
        ]);
        }catch(Exception $e){
            return response()->json([
                // 'message' => 'error',
                'response' => $e->getMessage(),
                // 'status' => 200
            ]);
        }
    }

}