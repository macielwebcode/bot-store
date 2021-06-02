<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Invoice
Route::resource('/invoices', 'InvoiceController', [
    'except' => ['edit', 'show', 'store', 'index']
]);

// Category
Route::get('/categories', [CategoryController::class,'index']);

// Product
Route::resource('/products', 'InvoiceController', [
    'except' => ['edit', 'show', 'store', 'create', 'update', 'destroy']
]);

Route::get('/products', [ProductController::class,'index']);

// Plan
Route::get('/plans', [PlanController::class,'index']);