<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'register']);
Route::post('/login', [AuthenticatedSessionController::Class, 'login']);

// Companies (only accessible for authenticated users with appropriate roles)
Route::middleware(['auth:sanctum', 'role:client|staff|administrator|super-user'])->group(function () {
    Route::get('/companies', [CompanyController::class, 'index'])->middleware('permission:browse companies');
    Route::post('/companies', [CompanyController::class, 'store'])->middleware('permission:add company');
    Route::get('/companies/{id}', [CompanyController::class, 'show'])->middleware('permission:read company');
    Route::put('/companies/{id}', [CompanyController::class, 'update'])->middleware('permission:edit company');
    Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->middleware('permission:delete company');
});

// Positions (open browsing, but protect other actions)
Route::get('/positions', [PositionController::class, 'index'])->middleware('permission:browse positions');
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/positions/{id}', [PositionController::class, 'show'])->middleware('permission:read position');
    Route::post('/positions', [PositionController::class, 'store'])->middleware('permission:add position');
    Route::put('/positions/{id}', [PositionController::class, 'update'])->middleware('permission:edit position');
    Route::delete('/positions/{id}', [PositionController::class, 'destroy'])->middleware('permission:delete position');
});

// Users (admin and staff only)
Route::middleware(['auth:sanctum', 'role:staff|administrator|super-user'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:browse users');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('permission:read user');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:add user');
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('permission:edit user');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('permission:delete user');
});
