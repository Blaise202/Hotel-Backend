<?php

namespace App\Http\Controllers;

use App\Models\StockItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Validator;

class ItemCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'description' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 404);
        }
        $result = StockItemCategory::create($request->all());
        if(!$result){
            return response()->json(['error'=>'Item category Was not saved'], 500);
        }
        return response()->json(['success'=>'Item category saved successfully'], 200);
    }

    public function editCategory($id)
    {
        $category = StockItemCategory::where('id',$id)->first();
        if(!$category){
            return response()->json(['error'=>'Error happened in fetching category data'], 404);
        }
        return response()->json(['success','category details'=>$category], 200);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = StockItemCategory::find($id);
        if(!$category){
            return response()->json(['error'=>'Error happened in fetching category data'], 404);
        }
        $validator = Validator::make($request->all(),[
            'name' => 'sometimes|string|unique:stock_item_categories,name',
            'description' => 'sometimes'
        ]);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 404);
        }
        $result = $category->update($request->all());
        if(!$result){
            return response()->json(['error'=>'This Category Was not updated'], 500);
        }
        $updates = StockItemCategory::where('id',$id)->get();
        return response()->json(['Category Updated Successfully', 'updates'=>$updates],200);
    }

    public function deleteCategory($id)
    {
        $category = StockItemCategory::find($id);
        
        if(!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }
        $category->status = 'deleted';
        $category->save();
    
        return response()->json(['message' => 'Category deleted successfully', 'category' => $category], 200);
    }
    

    public function showCategories()
    {
        $categories = StockItemCategory::where('status', 'active')->get();
        $count = $categories->count();
        if($count == 0){
            return response()->json(['success'=>'No Item Categories Found'], 200);
        }
        return response()->json(['Categories Found'=>$categories], 200);
    }

    public function searchCategory($search) {
        $query = StockItemCategory::query();
        $query->where('name', 'like', '%' . $search . '%');
        
        $items = $query->where('status', 'active')->get();
    
        if ($items->isEmpty()) {
            return response()->json(['message' => 'No categories found for the given criteria.'], 200);
        }
    
        return response()->json(['items' => $items], 200);
    }
    


}