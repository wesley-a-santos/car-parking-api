<?php

use App\Http\Controllers\Api\V1\Auth;
use App\Http\Controllers\Api\V1\ParkingController;
use App\Http\Controllers\Api\V1\VehicleController;
use App\Http\Controllers\Api\V1\ZoneController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', Auth\RegisterController::class);
        Route::post('login', Auth\LoginController::class);
    });

    Route::get('zones', [ZoneController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', [Auth\ProfileController::class, 'show']);
        Route::put('profile', [Auth\ProfileController::class, 'update']);

        Route::put('password', Auth\PasswordUpdateController::class);

        Route::post('auth/logout', Auth\LogoutController::class);

        Route::apiResource('vehicles', VehicleController::class);

        Route::prefix('parkings')->group(function () {
            Route::post('start', [ParkingController::class, 'start']);
            Route::get('history', [ParkingController::class, 'history']);
            Route::get('{parking}', [ParkingController::class, 'show']);
            Route::put('{parking}', [ParkingController::class, 'stop']);
            Route::get('/', [ParkingController::class, 'index']);
        });
    });
});
