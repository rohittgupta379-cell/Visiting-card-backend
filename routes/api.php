<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PersonalInfoController;


Route::post('/send-otp', [UserController::class, 'sendOtp']);
Route::post('/verify-otp', [UserController::class, 'verifyOtp']);


// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/personal-info', [PersonalInfoController::class, 'store']);
});