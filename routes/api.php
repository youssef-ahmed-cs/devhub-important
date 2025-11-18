<?php

use App\Http\Controllers\V1\CommentController;
use App\Http\Controllers\V1\PostController;
use App\Http\Controllers\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Auth\SocialiteMediaController;

Route::prefix('v1')->group(function () {

    Route::controller(SocialiteMediaController::class)->group(function () {
        Route::get('auth/google/login', 'login');
        Route::get('auth/google/callback', 'callback');
    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::middleware(['auth:api', 'throttle:15,1'])->group(function () {
            Route::post('logout', 'logout');
            Route::post('refresh', 'refreshToken');
            Route::post('me', 'user');
        });
    });

    Route::middleware(['auth:api', 'throttle:15,1'])->group(function () {
        Route::controller(PostController::class)->group(function () {
            Route::get('user/posts', 'userPosts');
            Route::get('search/posts', 'search');
            Route::delete('posts/{post}/force', 'forceDelete');
            Route::post('posts/{id}/restore', 'restore');
            Route::get('posts/recent', 'recentPosts');
        });
        Route::apiResource('posts', PostController::class);

        Route::controller(CommentController::class)->group(function () {
        Route::get('posts/{post}/comments', 'postComments');
        Route::get('users/{user}/comments', 'getByUser');
        Route::get('posts/{post}/comments', 'getByPost');
        Route::post('comments/{parentComment}/reply' , 'reply');
    });
    Route::apiResource('comments', CommentController::class);

    });
});
