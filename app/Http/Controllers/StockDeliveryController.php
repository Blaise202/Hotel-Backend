<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;

class StockDeliveryController extends Controller
{
    public function awaitingDeliveries(){
        $awatingDeliveries = Requisition::where('status', 'aproved')->get();
        if($awatingDeliveries->count() == 0){
            return response()->json([
                    'message' => 'success',
                    'responce' => "there is no awaiting delivery to be done",
                    'status' => 200
            ]);
        }
        return response()->json([
            'message' => 'success',
            'response' => $awatingDeliveries,
            'status' => 200
        ]);
    }

    // public function makeDelivery($recId){
    //     $id = Requisition::find($recId);
    // }
}