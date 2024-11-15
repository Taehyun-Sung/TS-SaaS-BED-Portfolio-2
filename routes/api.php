<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::group(['prefix'=>'v1'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::apiResource('companies', CompanyController::class);
    Route::patch('companies/{id}/restore', [CompanyController::class, 'restore'])
        ->name('companies.restore');
    Route::patch('companies/restore-all', [CompanyController::class, 'restoreAll'])
        ->name('companies.restore-all');
    Route::delete('companies/force-all',[CompanyController::class, 'deleteAll'])
        ->name('companies.delete-all');
});

Route::prefix('v1')->group(function () {
    Route::get('positions', [PositionController::class, 'index']);
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::apiResource('positions', PositionController::class)->except(['index']);
    Route::patch('positions/{id}/restore', [PositionController::class, 'restore'])
        ->name('positions.restore');
    Route::patch('positions/restore-all', [PositionController::class, 'restoreAll'])
        ->name('positions.restore-all');
    Route::delete('positions/force-all',[PositionController::class, 'deleteAll'])
        ->name('positions.delete-all');
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
//    Route::get('users', [UserController::class, 'index']);
//    Route::get('users/{user}', [UserController::class, 'show']);
//    Route::patch('users/{user}', [UserController::class, 'update']);
//    Route::delete('users/{user}', [UserController::class, 'destroy']);
    Route::apiResource('users', UserController::class);
    Route::patch('users/{id}/restore', [UserController::class, 'restore'])
        ->name('users.restore');
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])
        ->name('users.force-delete');
    Route::delete('/users/force-all', [UserController::class, 'destroyAll'])
        ->name('users.delete-all');
});


