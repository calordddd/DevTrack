<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SavedJobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/register/send-code', [AuthController::class, 'sendCode']);
Route::post('/register/verify-code', [AuthController::class, 'verifyCode']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/jobs/search', [JobController::class, 'search']);
    Route::get('/jobs/{id}', [JobController::class, 'show']);

    Route::apiResource('saved-jobs', SavedJobController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('applications', ApplicationController::class)->only(['index', 'store', 'update', 'destroy']);
    
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
});
