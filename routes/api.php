<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('posts', PostController::class);

Route::middleware('auth:sanctum')->post('/posts/{post}/change-status', [PostController::class, 'changeStatus']);

Route::apiResource('posts.comments', CommentController::class);

// post author can flag a comment
Route::middleware('auth:sanctum')->post('/posts/{post}/comments/{comment}/flag', [CommentController::class, 'flag']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// --- debug helpers for scope demonstration ---
Route::get('/debug/posts', function () {
    // bypass the global "published" scope to see every record
    return \App\Models\Post::withoutGlobalScope('published')->get();
});

Route::get('/debug/posts-with-comments', function () {
    // use the local scope defined on Post to eager‑load comments
    return \App\Models\Post::withComments()->get();
});