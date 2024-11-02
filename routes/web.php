<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'index', ['component' => 'create-post', 'title' => 'Create LinkedIn Post'])->name('create');
Route::view('/create', 'index', ['component' => 'create-post', 'title' => 'Create LinkedIn Post']);
Route::view('/copy-cat', 'index', ['component' => 'copy-cat', 'title' => 'Copy what works!']);
Route::view('/history', 'index', ['component' => 'post-history', 'title' => 'Post history']);
