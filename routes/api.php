<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController as ApiEventController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VolunteerController as ApiVolunteerController; // Import Controller Baru

// --- PUBLIC ROUTES (Tanpa Token) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/events', [ApiEventController::class, 'index']);

// TAMBAHAN BARU: Route Pendaftaran Relawan
Route::post('/events/{id}/volunteers', [ApiVolunteerController::class, 'store']);


// --- PROTECTED ROUTES (Wajib Pakai Token Organizer) ---
Route::middleware('auth:sanctum')->group(function () {
    // ... (Route organizer yang sudah ada biarkan saja)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/my-events', [ApiEventController::class, 'myEvents']);
    Route::get('/events/{id}/volunteers', [ApiVolunteerController::class, 'index']);
    Route::post('/events', [ApiEventController::class, 'store']);
    Route::post('/events/{id}', [ApiEventController::class, 'update']);
    Route::delete('/events/{id}', [ApiEventController::class, 'destroy']);
});