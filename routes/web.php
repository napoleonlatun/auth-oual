<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;

// Home page: show all posts with their user
Route::get('/', function () {
    $posts = Post::with('user')->latest()->get();
    return view('welcome', ['posts' => $posts]);
});

// Delete a post (only owner)
Route::delete('/post/{post}', function (Post $post) {
    if (auth()->id() !== $post->user_id) {
        abort(403);
    }
    $post->delete();
    return redirect('/');
});

// Edit form for a post (only owner)
Route::get('/post/{post}/edit', function (Post $post) {
    if (auth()->id() !== $post->user_id) {
        abort(403);
    }
    return view('edit-post', ['post' => $post]);
});

// Update post (only owner)
Route::put('/post/{post}', function (Post $post, Request $request) {
    if (auth()->id() !== $post->user_id) {
        abort(403);
    }

    $request->validate(['content' => 'required|max:255']);

    $post->update([
        'content' => $request->content,
    ]);

    return redirect('/');
});

// Auth routes
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'store']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);

// Create post
Route::post('/create-post', function (Request $request) {
    $request->validate(['content' => 'required|max:255']);

    Post::create([
        'content' => $request->content,
        'user_id' => auth()->id(),
    ]);

    return redirect('/');
});

// Follow a user
Route::post('/follow/{user}', function (User $user) {
    auth()->user()->following()->attach($user->id);
    return back();
});

// Unfollow a user
Route::post('/unfollow/{user}', function (User $user) {
    auth()->user()->following()->detach($user->id);
    return back();
});
