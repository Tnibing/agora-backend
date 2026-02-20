<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:60,1')->group(function () {

    Route::prefix('/articles')->group( function () {
        Route::get('/main', [ArticleController::class, 'main']);
        Route::get('/{id}', [ArticleController::class, 'show']);
        Route::get('/category/{category}', [ArticleController::class, 'indexByCategory']);
    });

    Route::get('/tags', [TagController::class, 'index']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::prefix('/comments')->group(function () {
            Route::post('/', [CommentController::class, 'store']);
            //Route::patch('/', [CommentController::class, 'update']);
        });
    });

    Route::prefix('/user')->group(function () {
        Route::post('/', [UserController::class, 'store']);

        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/delete-request', [UserController::class, 'hasDeleteRequest']);
            Route::post('/delete-request', [UserController::class, 'deleteRequest']);
            Route::delete('/delete-request', [UserController::class, 'cancelDeleteRequest']);
            Route::patch('/', [UserController::class, 'update']);
            Route::get('/{id}', [UserController::class, 'show']);
        });
    });
});
