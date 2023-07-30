<?php

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

Route::post('/login' , [\App\Http\Controllers\Api\AuthController::class , 'login']);
Route::post('/register' , [\App\Http\Controllers\Api\AuthController::class , 'register']);

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout' , [\App\Http\Controllers\Api\AuthController::class , 'logout']);

    //Profile
    Route::get('/profile' , [\App\Http\Controllers\Api\ProfileController::class , 'show']);
    Route::put('/profile' , [\App\Http\Controllers\Api\ProfileController::class , 'update']);

    //Card
    Route::get('/cards' , [\App\Http\Controllers\Api\CardController::class , 'index']);
    Route::post('/cards' , [\App\Http\Controllers\Api\CardController::class , 'store']);
    Route::delete('/cards/{card}' , [\App\Http\Controllers\Api\CardController::class , 'destroy']);

    //Order
    Route::post('/orders' , [\App\Http\Controllers\Api\OrderController::class , 'store']);
    Route::get('/orders/checkout' , [\App\Http\Controllers\Api\OrderController::class , 'orderCheckout']);

    //Order Item
    Route::put('/orders/item/{id}/increase' , [\App\Http\Controllers\Api\OrderItemController::class , 'increaseQuantity']);
    Route::put('/orders/item/{id}/decrease' , [\App\Http\Controllers\Api\OrderItemController::class , 'decreaseQuantity']);
    Route::delete('/orders/item/{id}' , [\App\Http\Controllers\Api\OrderItemController::class , 'destroy']);

    //Payment
    Route::post('/payment' , [\App\Http\Controllers\Api\PaymentController::class , 'payment']);
    Route::post('/result' , [\App\Http\Controllers\Api\PaymentController::class , 'result']);
    Route::get('/result/{ref_number}' , [\App\Http\Controllers\Api\PaymentController::class , 'getResult']);
});

//Product
Route::get('/products' , [\App\Http\Controllers\Api\ProductController::class , 'index']);
