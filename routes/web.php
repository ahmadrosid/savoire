<?php

use App\Livewire\CopyCat;
use App\Livewire\CreatePost;
use App\Livewire\PostHistory;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

$routes = function() {
    Route::get('/', CreatePost::class);
    Route::get('/create', CreatePost::class)->name('create');
    Route::get('/copy-cat', CopyCat::class)->name('copy-cat');
    Route::get('/history', PostHistory::class)->name('history');
    
    Route::get('/post/{post}', function (Post $post) {
        return view('detail', ['post' => $post]);
    })->name('post.detail');

    Route::get('/post/edit/{post}', function (Post $post) {
        return view('index', ['component' => 'edit-post', 'post' => $post]);
    })->name('post.edit');
};

app()->environment('production') 
    ? Route::middleware('auth')->group($routes) 
    : Route::group([], $routes);
