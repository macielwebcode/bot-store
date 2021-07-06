<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProductController;

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

// Auth related 
Route::prefix("auth")->group(function() {
    Route::post("login", [AuthController::class, "login"]);
    Route::post("register", [AuthController::class, "register"]);
    Route::post("forgot", [AuthController::class, "forgot"]);
    Route::post("reset", [AuthController::class, "reset"]);
});

Route::prefix("admin")->group(function() {
    Route::resource("plans", \App\Http\Controllers\Admin\PlanController::class, [
        'except' => ['create', 'destroy', 'edit']
    ]);
    
    Route::resource("users", \App\Http\Controllers\Admin\UserController::class, [
        'except' => [ 'create', 'store', 'destroy', 'edit']
    ]);
    // Support routes for handle the user behavior
    Route::prefix("users")->group(function() {
        Route::post("toggleActive/{user}", [\App\Http\Controllers\Admin\UserController::class, "toggleActive"]);
    });


    Route::prefix("subscriptions")->group(function() {
        Route::post("cancel/{subscription}", [\App\Http\Controllers\Admin\SubscriptionController::class, "cancel"]);
    });
    Route::resource("subscriptions", \App\Http\Controllers\Admin\SubscriptionController::class);
});


// Recebimento de POSTBACK's
Route::post("pb_pagarme", [PagarmeController::class, 'watch']);

// Pagarme Control access
// Route::prefix("pagarme")->group(function() {
//     Route::resource("plans", PlansController::class);
//     // Route::resource("products", ProductsController::class);
//     // Route::resource("billings", BillingController::class);
// });

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

    Route::prefix("products")->group(function() {
        Route::get("/favorites", [ProductController::class, 'favorites']);
        Route::post("/favorite/{product}", [ProductController::class, 'setFavorite']);

        Route::get("/actives", [ProductController::class, 'actives']);
        Route::post("/active/{product}", [ProductController::class, 'setActive']);
    });
    Route::resource('products', ProductController::class, [
        'except' => ['edit', 'show', 'store', 'create', 'update', 'destroy']
    ]);

    // Handle user notifications
    Route::prefix("notifications")->group(function() {
        Route::get("read/{notification}", \App\Http\Controllers\NotificationController::class . "@setRead");
        Route::get("unread", [NotificationController::class, "unread"]);
    });
    Route::resource('notifications', NotificationController::class, [
        'except' => ['create', 'destroy', 'store']
    ]);

});


// Auth is not mandatory
// Category
Route::get('/categories', [CategoryController::class,'index']);

// Product
Route::get('/products', [ProductController::class,'index']);

// Plan
Route::get('/plans', [PlanController::class,'index']);