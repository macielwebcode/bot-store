<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PagarMe\PlansController;
use App\Http\Controllers\Receiver\PagarmeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TransactionController;
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

Route::post("login", [AuthController::class, "login"]);
Route::post("register", [AuthController::class, "register"]);


Route::prefix("admin")->group(function() {
    Route::resource("plans", \App\Http\Controllers\Admin\PlanController::class);
});

Route::resource("receiver/pagarme", PagarmeController::class);

// Paypal Control access
// Route::prefix("paypal")->group(function() {
//     Route::resource("products", ProductsController::class);
//     Route::resource("plans", PlansController::class);
//     Route::resource("billings", BillingController::class);
// });

// Pagarme Control access
Route::prefix("pagarme")->group(function() {
    Route::resource("plans", PlansController::class);
    // Route::resource("products", ProductsController::class);
    // Route::resource("billings", BillingController::class);
});

// Auth is mandatory
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, "user"]);
    Route::post('logout', [AuthController::class, "logout"]);

    Route::resource('subscriptions', SubscriptionController::class, [
        'except' => ['edit', 'show', 'create']
    ]);

    Route::resource('transactions', TransactionController::class, [
        'except' => ['edit', 'show', 'create']
    ]);

    Route::resource('/products', 'ProductController', [
        'except' => ['edit', 'show', 'store', 'create', 'update', 'destroy']
    ]);

    Route::get("/products/favorites", [ProductController::class, 'favorites']);
    Route::post("/products/favorite", [ProductController::class, 'setFavorite']);

    Route::get("/products/actives", [ProductController::class, 'actives']);
    Route::post("/products/active", [ProductController::class, 'setActive']);

    // Route::
});


// Auth is not mandatory
// Category
Route::get('/categories', [CategoryController::class,'index']);

// Product
Route::get('/products', [ProductController::class,'index']);

// Plan
Route::get('/plans', [PlanController::class,'index']);