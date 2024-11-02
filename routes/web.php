<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::view('/', 'index', ['component' => 'create-post', 'title' => 'Create LinkedIn Post'])->name('create');
Route::view('/create', 'index', ['component' => 'create-post', 'title' => 'Create LinkedIn Post']);
Route::view('/copy-cat', 'index', ['component' => 'copy-cat', 'title' => 'Copy what works!']);
Route::view('/history', 'index', ['component' => 'post-history', 'title' => 'Post history']);

Route::get('/post/{post}', function (Post $post) {
    return view('detail', ['post' => $post]);
})->name('post.detail');
