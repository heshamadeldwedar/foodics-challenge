<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IngredientController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/ingredients', [IngredientController::class, 'index']);
Route::get('/ingredients/{id}', [IngredientController::class, 'show']);
