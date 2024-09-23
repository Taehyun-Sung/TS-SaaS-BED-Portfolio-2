<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('/register', [RegisteredUserController::class, 'register']); // User registration
Route::post('/login', [AuthenticatedSessionController::class, 'login']); // User login
Route::post('/logout', [AuthenticatedSessionController::class, 'logout']); // User logout

// Company Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Client: Can only access their own companies
    Route::middleware('role:client')->group(function () {
        Route::get('/companies', [CompanyController::class, 'index']); // Search own companies
        Route::get('/companies/{id}', [CompanyController::class, 'show']); // Read own company
        Route::post('/companies', [CompanyController::class, 'store']); // Add own company
        Route::put('/companies/{id}', [CompanyController::class, 'update']); // Edit own company
        Route::delete('/companies/{id}', [CompanyController::class, 'destroy']); // Delete own company
    });

    // Staff, Administrator, and Super User: Full access to all companies
    Route::middleware('role:staff|administrator|super-user')->group(function () {
        Route::get('/companies', [CompanyController::class, 'index']); // Search all companies
        Route::get('/companies/{id}', [CompanyController::class, 'show']); // Read any company
        Route::post('/companies', [CompanyController::class, 'store']); // Add any company
        Route::put('/companies/{id}', [CompanyController::class, 'update']); // Edit any company
        Route::delete('/companies/{id}', [CompanyController::class, 'destroy']); // Delete any company
    });
});

// Position Routes
Route::get('/positions', [PositionController::class, 'index']); // Public access to all positions
Route::middleware(['auth:sanctum'])->group(function () {
    // All authenticated users can read one position
    Route::get('/positions/{id}', [PositionController::class, 'show']); // Read one position

    // Client: Can only access their own positions
    Route::middleware('role:client')->group(function () {
        Route::post('/positions', [PositionController::class, 'store']); // Add own position
        Route::put('/positions/{id}', [PositionController::class, 'update']); // Edit own position
        Route::delete('/positions/{id}', [PositionController::class, 'destroy']); // Delete own position
    });

    // Staff, Administrator, and Super User: Full access to all positions
    Route::middleware('role:staff|administrator|super-user')->group(function () {
        Route::get('/positions', [PositionController::class, 'index']); // Get all positions
        Route::post('/positions', [PositionController::class, 'store']); // Add any position
        Route::put('/positions/{id}', [PositionController::class, 'update']); // Edit any position
        Route::delete('/positions/{id}', [PositionController::class, 'destroy']); // Delete any position
    });
});

// User Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Staff, Administrator, and Super User: Full access to manage users
    Route::middleware('role:staff|administrator|super-user')->group(function () {
        Route::get('/users', [UserController::class, 'index']); // Browse all users
        Route::get('/users/{id}', [UserController::class, 'show']); // Read any user
        Route::post('/users', [UserController::class, 'store']); // Add user
        Route::put('/users/{id}', [UserController::class, 'update']); // Edit any user
        Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete any user
    });

    // Applicant and Client: Limited access to their own user data
    Route::middleware('role:applicant|client')->group(function () {
        Route::get('/users/{id}', [UserController::class, 'show']); // Read own user
        Route::put('/users/{id}', [UserController::class, 'update']); // Edit own user
        Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete own user
    });
});
