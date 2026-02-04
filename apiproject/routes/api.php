<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalesController;
use App\Http\Controllers\DuenoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas para Animales
Route::apiResource('animales', AnimalesController::class);

// Rutas para Dueños
Route::apiResource('duenos', DuenoController::class);

