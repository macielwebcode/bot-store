<?php

use App\Http\Controllers\AuthController;
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

Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);

// Auth is mandatory
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, "user"]);
    Route::post('logout', [AuthController::class, "logout"]);

    Route::resource('invoices', InvoiceController::class, [
        'except' => ['edit', 'show', 'create']
    ]);

    Route::resource('/products', 'ProductController', [
        'except' => ['edit', 'show', 'store', 'create', 'update', 'destroy']
    ]);

    Route::get("/products/favorites", [ProductController::class, 'favorites']);
    Route::post("/products/favorite", [ProductController::class, 'setFavorite']);

});


// Auth is not mandatory
// Category
Route::get('/categories', [CategoryController::class,'index']);

// Product
Route::get('/products', [ProductController::class,'index']);

// Plan
Route::get('/plans', [PlanController::class,'index']);