<?php

use App\Livewire\CopyCat;
use App\Livewire\CreatePost;
use App\Livewire\EditPost;
use App\Livewire\PostHistory;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

$routes = function() {
    Route::get('/', CreatePost::class);
    Route::get('/create', CreatePost::class)->name('create');
    Route::get('/copy-cat', CopyCat::class)->name('copy-cat');
    Route::get('/history', PostHistory::class)->name('history');
    Route::get('/post/edit/{post}', EditPost::class)->name('post.edit');

    Route::get('/post/{post}', function (Post $post) {
        return view('detail', ['post' => $post]);
    })->name('post.detail');
};

app()->environment('production') 
    ? Route::middleware('auth')->group($routes) 
    : Route::group([], $routes);
