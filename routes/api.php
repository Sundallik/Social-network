<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostImageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/authorized', [UserController::class, 'getAuthorizedUser']);
    Route::post('/users/toggle_following', [UserController::class, 'toggleFollowing']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/users/{id}/posts', [UserController::class, 'getPosts']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/followings_posts', [PostController::class, 'followingsPosts']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::post('/posts/{post}/toggle_like', [PostController::class, 'toggleLike']);
    Route::get('/posts/{post}/comments', [PostController::class, 'getComments']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);

    Route::post('/post_image', [PostImageController::class, 'store']);
    Route::delete('/post_image', [PostImageController::class, 'clear']);
});
