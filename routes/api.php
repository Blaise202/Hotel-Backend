<?php

use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\StockItemController;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'Items'], function () {
    Route::get('/', [StockItemController::class, 'showItems'])->name('item.show');
    Route::post('/create', [StockItemController::class, 'store'])->name('item.create');
});

Route::group(['prefix'=>'categories'], function() {
    Route::get('/', [ItemCategoryController::class, 'showCategories'])->name('category.show');
    Route::post('/create', [ItemCategoryController::class, 'store'])->name('category.create');
    Route::get('/edit/{id}', [ItemCategoryController::class, 'editCategory'])->name('category.edit');
    Route::post('/update/{id}', [ItemCategoryController::class, 'updateCategory'])->name('category.update');
    Route::get('/delete/{id}', [ItemCategoryController::class, 'deleteCategory'])->name('category.delete');
    Route::get('/search/{search}', [ItemCategoryController::class, 'searchCategory'])->name('category.search');
});

Route::group(['prefix' => 'suppliers'], function(){
    Route::get('/', [SupplierController::class, 'showSuppliers'])->name('suppliers.show');
    Route::post('/add', [SupplierController::class, 'addSupplier'])->name('suppliers.addnew');

});