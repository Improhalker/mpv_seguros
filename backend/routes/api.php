<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\LeadInteractionController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:6,1')->group(function (): void {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::get('/dashboard', [DashboardController::class, 'show']);

Route::get('/leads', [LeadController::class, 'index']);
Route::post('/leads', [LeadController::class, 'store']);
Route::get('/leads/{lead}', [LeadController::class, 'show']);
Route::put('/leads/{lead}', [LeadController::class, 'update']);
Route::delete('/leads/{lead}', [LeadController::class, 'destroy']);

Route::get('/leads/{lead}/interactions', [LeadInteractionController::class, 'index']);
Route::post('/leads/{lead}/interactions', [LeadInteractionController::class, 'store']);
Route::put('/leads/{lead}/interactions/{interaction}', [LeadInteractionController::class, 'update']);
Route::delete('/leads/{lead}/interactions/{interaction}', [LeadInteractionController::class, 'destroy']);
