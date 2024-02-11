<?php

namespace App\Http\Controllers;

use App\Models\StockSupplier;
use App\Models\SupplierAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{

    public function showSuppliers()
    {
        $suppliers = StockSupplier::all()->where('status', 'active');
        $count = $suppliers->count();
        if($count == 0){
            return response()->json('NO suppliers Saved yet');
        }
        return response()->json(['suppliers'=>$suppliers]);
    }
    public function addSupplier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|unique:stock_suppliers,phone',
            'email' => 'required|email|unique:stock_suppliers,email',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zipcode' => 'nullable|string',
            'country' => 'nullable|string',
            'image' => 'required|file|mimes:jpg,png,jpeg,avif|max:2048|unique:stock_suppliers,image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $supplier = new StockSupplier();
        $supplier->name = $request->name;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        
        
        $image = $request->file('image');
        $filename = $supplier->name . '_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move('suppliers', $filename);
        $supplier->image = $filename;
        
        $supplier->save();
        
        $supplier->supplierAddress()->updateOrCreate([
            'city' => $request->city,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
            'country' => $request->country,
            'address' => $request->address,
        ]);
        return response()->json(['message' => 'Supplier added successfully', 'supplier' => $supplier], 201);
    }

    public function update(Request $request, $id)
    {
        $supplier = StockSupplier::find($id);
        $validate = Validator::make($request->all(),[
            'name' => 'sometimes|string|unique:stock_suppliers,name',
            'contact' => 'sometimes|string',
            'phone' => 'sometimes|numeric|unique:stock_suppliers,phone',
            'email' => 'sometimes|email|unique:stock_suppliers,email',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'state' => 'sometimes|string',
            'zipcode' => 'sometimes|string',
            'country' => 'sometimes|string',
            'image' => 'sometimes|image|mimes:png,jpg,jpeg,avif|max:2048',
        ]);
        if($validate->fails()){
            return response()->json(['errors'=>$validate->errors()], 400);
        }
        if($request->has(['image'])){
            $realName = $supplier->name;
            $imageName = $realName . '_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/suppliers'), $imageName);
            $supplier->image = $imageName;
        }
        
        $supplier->update($request->all());
            $supplierAddress = SupplierAddress::where('stock_supplier_id',$id);
            if($supplierAddress->count() > 0){
                $supplierAddress->update([
                    'city' => $request->city,
                    'state' => $request->state,
                    'zipcode' => $request->zipcode,
                    'country' => $request->country,
                    'address' => $request->address
                ]);
            }else{
                $supplier->supplierAddress()->updateOrCreate([
                    'city' => $request->city,
                    'state' => $request->state,
                    'zipcode' => $request->zipcode,
                    'country' => $request->country,
                    'address' => $request->address,
                ]);
            }
            
        $updates = StockSupplier::find($id);
        $address = SupplierAddress::where('stock_supplier_id', $id)->first();
        return response()->json(['success', 'updates'=>$updates,'address'=>$address], 200);
    }

    public function ViewSupplier($id)
    {
        $supplier = StockSupplier::where('id',$id)->get();
        $supplierAddress = SupplierAddress::where('stock_supplier_id', $id)->get();
        if(!$supplier){
            return response()->json(['error'=>'Supplier not found'], 404);
        }
        return response()->json(['message', 'supplier data'=>$supplier, 'supplier_address'=>$supplierAddress], 200);
    }

    public function searchSupplier($search) {
        $query = StockSupplier::query();
        $query->where('name', 'like', '%' . $search . '%');
        
        $items = $query->where('status', 'active')->get();
    
        if ($items->isEmpty()) {
            return response()->json(['message' => 'No suppliers found for the given criteria.'], 200);
        }
    
        return response()->json(['items' => $items], 200);
    }

    public function deleteSupplier($id)
    {
        $supplier = StockSupplier::find($id);
        
        if(!$supplier) {
            return response()->json(['error' => 'supplier not found'], 404);
        }
        $supplier->status = 'deleted';
        $supplier->save();
    
        return response()->json(['message' => 'supplier deleted successfully', 'supplier' => $supplier], 200);
    }
    
}