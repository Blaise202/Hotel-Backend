<?php

use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\RequisitionController;
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
    Route::get('/ShowOne/{id}', [StockItemController::class, 'ShowOneItem'])->name('item.showOne');
    Route::post('/create', [StockItemController::class, 'store'])->name('item.create');
    Route::put('/update/{id}', [StockItemController::class, 'updateItem'])->name('item.update');
    Route::put('/updateQ/quantity', [StockItemController::class, 'updateItemQuantity'])->name('item.update.quantity');
    Route::get('/delete/{id}', [StockItemController::class, 'deleteItem'])->name('item.delete');
    // Route::delete('/destroy/{id}', [StockItemController::class, 'destroyItem'])->name('item.destroy');
    Route::post('/importItem/{id}', [StockItemController::class, 'importItem'])->name('item.import');
    Route::post('/exportItem/{id}', [StockItemController::class, 'exportItem'])->name('item.export');
});

Route::group(['prefix'=>'requisitions'], function() {
    Route::get('/', [RequisitionController::class, 'showRequisitions'])->name('requisitions');
    Route::post('/make', [RequisitionController::class, 'makeRequisition'])->name('requisitions.create');
    Route::put('/approve/{id}', [RequisitionController::class, 'ApproveRequisition'])->name('requisitions.approve');
    Route::put('/decline/{id}', [RequisitionController::class, 'DeclineRequisition'])->name('requisitions.decline');
    Route::get('/history', [RequisitionController::class, 'AllRequisitions'])->name('requisitions.history');
});

Route::group(['prefix'=>'categories'], function() {
    Route::get('/', [ItemCategoryController::class, 'showCategories'])->name('category.show');
    Route::post('/create', [ItemCategoryController::class, 'store'])->name('category.create');
    Route::get('/edit/{id}', [ItemCategoryController::class, 'editCategory'])->name('category.edit');
    Route::put('/update/{id}', [ItemCategoryController::class, 'updateCategory'])->name('category.update');
    Route::delete('/delete/{id}', [ItemCategoryController::class, 'deleteCategory'])->name('category.delete');
    Route::get('/search/{search}', [ItemCategoryController::class, 'searchCategory'])->name('category.search');
});

Route::group(['prefix' => 'suppliers'], function() {
    Route::get('/', [SupplierController::class, 'showSuppliers'])->name('suppliers.show');
    Route::post('/create', [SupplierController::class, 'addSupplier'])->name('suppliers.addnew');
    Route::get('/edit/{id}', [SupplierController::class, 'ViewSupplier'])->name('suppliers.viewSupplier');
    Route::put('/update/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/delete/{id}', [SupplierController::class, 'deleteSupplier'])->name('supplier.delete');
    Route::get('/search/{search}', [SupplierController::class, 'searchSupplier'])->name('supplier.search');
});