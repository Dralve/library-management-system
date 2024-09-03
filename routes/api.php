<?php

use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\BorrowRecordController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\RatingController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Auth\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register' , [AuthController::class , 'register']);
Route::post('/auth/login' , [AuthController::class , 'login']);

Route::middleware(['auth'])->group(function(){
    Route::post('/auth/logout', [AuthController::class , 'logout']);
});

Route::prefix('/v1')->group(function () {
    Route::apiResource('/books', BookController::class);
    Route::get('filter/books', [BookController::class, 'filter']);
});

Route::middleware('jwt.auth')->group(function () {
    Route::apiResource('borrow-records', BorrowRecordController::class);
});


Route::prefix('/v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::put('/user', [UserController::class, 'update']);
    });

    Route::middleware(['auth:api', 'admin'])->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{user}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{user}/role', [UserController::class, 'updateRole']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });
});

Route::prefix('/v1')->group(function () {
    Route::apiResource('/categories', CategoryController::class);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/ratings', [RatingController::class, 'store']);
    Route::get('/ratings/{rating}', [RatingController::class, 'show']);
    Route::put('/ratings/{rating}', [RatingController::class, 'update']);
    Route::delete('/ratings/{rating}', [RatingController::class, 'destroy']);
});


