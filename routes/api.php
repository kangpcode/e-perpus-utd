<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\StatsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/
Route::get('/stats', [StatsController::class, 'publicStats']);
Route::get('/categories', [BookController::class, 'categories']);
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{slug}', [BookController::class, 'show']);

// Authentication
Route::post('/auth/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected API Routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Books Management (Admin & Pustakawan only)
    Route::middleware('role:admin|pustakawan')->group(function () {
        Route::post('/books', [BookController::class, 'store']);
        Route::delete('/books/{id}', [BookController::class, 'destroy']);
    });

    // Book Reviews (Civitas akademika / authenticated users)
    Route::post('/books/{id}/reviews', [BookController::class, 'addReview']);

    // Loans / Circulation
    Route::get('/loans', [LoanController::class, 'index']);
    Route::post('/loans/borrow', [LoanController::class, 'borrow']);
    Route::post('/loans/{id}/extend', [LoanController::class, 'extend']);
    Route::post('/loans/{id}/return', [LoanController::class, 'returnBook']);
});
