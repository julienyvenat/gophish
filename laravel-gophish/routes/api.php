<?php

use App\Http\Controllers\Api\TemplateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Templates API
// In a real app, protect with auth:sanctum
Route::apiResource('templates', TemplateController::class);
