<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\EmployeeController;
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

Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:user'], function () {
    Route::get('me', [ProfileController::class, 'me']);
    Route::post('start-working', [ProfileController::class, 'startWorking']);
    Route::put('subscribe-categories', [ProfileController::class, 'subscribeCategories']);

    Route::resource('addresses', AddressController::class)->only(['index', 'store']);

    // TODO just dummy routes and not a good practices
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::put("orders/{order}/assign", [OrderController::class, "assignEmployee"]);
    // TODO just dummy routes and not a good practices

    Route::resource('orders', OrderController::class)->only(['index', 'store', 'show']);
    Route::post('orders/{order}/ratings', [OrderController::class, 'giveRatings']);

    Route::resource('categories', CategoryController::class)->only(['index', 'show']);

    Route::resource('employees', EmployeeController::class)->only(['index', 'show']);
});
