<?php

namespace App\Http\Controllers;

use App\Http\Traits\NotificationTrait;
use App\Models\Requisition;
use App\Models\StockItem;
use App\Models\StockItemQuantity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RequisitionController extends Controller
{

    use NotificationTrait;

    public function showRequisitions(){
        $requisitions = Requisition::where('status', 'pending')->get();
        $notifications = [];
        $count = $requisitions->count();
        if($count == 0){
            return response()->json([
                'message' => 'success',
                'response' => 'No pending Requisitions',
                'status' => 200
            ]);
        }
        foreach($requisitions as $requisition){
            $notification = $this->getNotification($requisition);
            if($notification){
                $notifications[] = $notification;
            }
        }
        return response()->json([
            'message' => 'success',
            'response' => $requisitions,
            'notifications' => $notifications ?: 0,
            'status' => 200
        ]);
    }
    

    public function makeRequisition(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stock_item_id' => 'required|exists:stock_items,id',
            'quantity' => 'required|numeric',
            'expected_deadline' => 'date|date_format:Y-m-d'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'error',
                'response' => $validator->errors(),
                'status' => 400
            ]);
        }

        $requisition = StockItem::findOrFail($request->stock_item_id);
        $initialQuantity = $requisition->ItemQuantity->quantity;

        if ($initialQuantity < $request->quantity || $request->quantity < 0) {
            return response()->json([
                'message' => 'error',
                'response' => 'Amount of requisition is invalid: ' . $request->quantity,
                'available_quantity' => $initialQuantity,
                'status' => 400
            ]);
        }

        $requisition->Requisition()->create([
            'quantity' => $request->quantity,
            'expected_deadline' => $request->expected_deadline
        ]);

        return response()->json([
            'message' => 'success',
            'response' => 'Requisition Made Successfully',
            'data' => $request->all(),
            'status' => 200
        ]);
    }

    public function ApproveRequisition($id)
    {
        $requisition = Requisition::findOrFail($id);
        $requisition_qty = $requisition->quantity;
        $requisition_item_id = $requisition->stock_item_id;
        $initial_qty = StockItemQuantity::where('stock_item_id',$requisition_item_id)->first()->quantity;
        if($requisition_qty > $initial_qty){
            return response()->json([
                'message' => 'error',
                'response' => 'Requisition amount exceeds the available in the stock! Please Notify',
                'status' => 400
            ]);
        }

        $stock_item = StockItem::findOrFail( $requisition_item_id);

        $stock_item->ItemQuantity()->updateOrCreate(
            [],
            ['quantity'=> DB::raw("quantity - $requisition_qty")]
        );

        $newQuantity = StockItemQuantity::where('stock_item_id', $requisition_item_id)->first()->quantity;

        $stock_item->StockExport()->create([
            'quantity' => $requisition_qty,
            'export_date' => Carbon::now()
        ]);

        $requisition->status = 'approved';
        $requisition->save();

        return response()->json([
            'message' => 'success',
            'response' => 'requisition Approved Successfully',
            'updated quantity' => $newQuantity,
            'status' => 200
        ]);
    }

    public function DeclineRequisition($id, Request $request)
    {
        $requisition = Requisition::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'reason' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'error',
                'response' => $validator->errors(),
                'status' => 400
            ]);
        }
        $requisition->reason = $request->reason;
        $requisition->status = 'declined';
        $requisition->save();
        return response()->json([
            'message' => 'success',
            'response' => 'requisition declined SuccessFully',
            'status' => 200
        ]);
    }

    public function AllRequisitions()
    {
        $requisitions = Requisition::all();
        $count = $requisitions->count();
        if($count == 0){
            return response()->json([
                'message' => 'success',
                'response' => 'nothing is in requisition history',
                'status' => 200
            ]);
        }
        return response()->json([
            'message' => 'success',
            'response' => $requisitions,
            'status' => 200
        ]);
    }

}