<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;


Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
