<?php

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\ApiLogController;
use Illuminate\Support\Facades\Route;

// * Auth routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('logout', [AuthController::class, 'logout']);
    });
});

// * Weather routes
Route::middleware(['auth:sanctum', 'log_api_request'])->group(function () {
    Route::get('weather/current', [ApiController::class, 'getCurrentWeatherData']);
});

// * log routes
Route::group(['prefix' => 'logs'], function () {
    Route::get('/', [ApiLogController::class, 'getLogs']);
    Route::get('/{id}', [ApiLogController::class, 'getLog']);
    Route::post('/', [ApiLogController::class, 'logRequest']);
})->middleware(['auth:sanctum', 'ability:admin']); // * only admin can access logs
