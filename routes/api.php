<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\PersonalInfoController;


Route::post('/send-otp', [UserController::class, 'sendOtp']);
Route::post('/verify-otp', [UserController::class, 'verifyOtp']);


// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/personal-info', [PersonalInfoController::class, 'index']);
    Route::get('/personal-info-default', [PersonalInfoController::class, 'getDefault']);
    Route::post('/personal-info', [PersonalInfoController::class, 'store']);
    Route::put('/personal-info/{id}', [PersonalInfoController::class, 'update']);
    Route::put('/personal-info/{id}/default', [PersonalInfoController::class, 'default']);
    Route::delete('/personal-info/{id}', [PersonalInfoController::class, 'destroy']);



    Route::get('/cards', [CardController::class, 'index']);
    Route::post('/cards', [CardController::class, 'store']);
    Route::delete('/cards/{card_id}', [CardController::class, 'delete']);
});