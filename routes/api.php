<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EnquiryController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

// Enquiry data contains residents' personal information; only council staff may access it.
Route::middleware(['auth:sanctum', 'council.staff'])->group(function () {
    Route::get('/enquiries', [EnquiryController::class, 'index']);
    Route::post('/enquiries', [EnquiryController::class, 'store']);
    Route::get('/enquiries/{enquiry}', [EnquiryController::class, 'show']);
    Route::put('/enquiries/{enquiry}', [EnquiryController::class, 'update']);
    Route::delete('/enquiries/{enquiry}', [EnquiryController::class, 'destroy']);
});
