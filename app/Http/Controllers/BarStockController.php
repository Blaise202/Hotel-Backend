<?php

namespace App\Http\Controllers;

use App\Models\BarStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarStockController extends Controller
{
    public function index()
    {
        $barStocks = BarStock::where('status', 'open')->get();
        $count = $barStocks->count();
        if($count == 0){
            return response()->json([
                'message' => 'error',
                'response' => 'there are no bars stored in our database',
                'status' => 400
            ]);
        }
        return response()->json([
            'message' => 'success',
            'response' => $barStocks,
            'status' => 200
        ]);
    }
    public function create(Request $request){
        $barStock = new BarStock();
        $validator = Validator::make($request->all(), ['name' => 'required|unique:bar_Stocks,name,except,id']);
        if($validator->fails()){
            return response()->json([
                'message' => 'error',
                'response' => $validator->errors(),
                'status' => 400
            ]);
        }
        $barStock->name = $request->name;
        $barStock->status = 'open';
        $barStock->save();
        return response()->json([
            'message' => 'success',
            'response' => 'Bar Added Successfully',
            'status' => 200
        ]);
    }

    public function update(Request $request, $id){
        $barId = BarStock::find($id);
        $validator = Validator::make($request->all(), ['name'=>'required|unique:bar_stocks,name']);
        if($validator->fails()){
            return response()->json([
                'message' => 'error',
                'response' => $validator->errors(),
                'status' => 400
            ]);
        }
        $barId->name = $request->name;
        $barId->save();
        return response()->json([
            'message' => 'success',
            'response' => 'Bar Updated Successfully',
            'updates' => $barId,
            'status' => 200
        ]);
    }
    
    public function close($id, Request $request){
        $validator = Validator::make($request->all(), ['reason'=>'required|string|min:20']);
        if($validator->fails()){
            return response()->json([
                'message' => 'error',
                'response' => $validator->errors(),
                'status' => 400
            ]);
        }
        $barId = BarStock::find($id);
        $barId->status = 'closed';
        $barId->reason = $request->reason;
        $barId->save();
        return response()->json([
            'message' => 'success',
            'response' => 'Bar '. $barId->name .' Closed Successfully',
            'status' => 200
        ]);
    }

    public function destroy($id){
        $barId = BarStock::find($id);
        $barId->delete();
        return response()->json([
            'message' => 'success',
            'response' => 'Bar Closed Successfully',
            'status' => 200
        ]);
    }
}