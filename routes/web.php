<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Post;
Route::get('/', function () {
    $posts = Post::with('user')->latest()->get();
    return view('welcome', ['posts' => $posts]);
});
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'store']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/create-post', function (\Illuminate\Http\Request $request) {
    $request->validate(['content' => 'required|max:255']);
    Post::create([
        'content' => $request->content,
        'user_id' => auth()->id()
    ]);
    return redirect('/');
});