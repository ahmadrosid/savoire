<?php

use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\StreamController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Livewire\PostHistory;
use App\Livewire\CreatePost;
use App\Livewire\EditPost;
use App\Livewire\CopyCat;
use App\Models\Post;
use App\Livewire\Admin\TemplateManager;

$routes = function() {
    Route::get('/', CreatePost::class);
    Route::get('/home', CreatePost::class);
    Route::get('/create', CreatePost::class)->name('create');
    Route::get('/copy-cat', CopyCat::class)->name('copy-cat');
    Route::get('/history', PostHistory::class)->name('history');
    Route::get('/post/edit/{post}', EditPost::class)->name('post.edit');
    Route::get('/post/{post}', fn (Post $post) => view('detail', ['post' => $post]))->name('post.detail');
    Route::post('/chat/stream', StreamController::class)->name('stream');

    // Admin routes
    Route::middleware([AdminMiddleware::class])->prefix('admin')->group(function () {
        Route::get('/templates', TemplateManager::class)->name('admin.templates');
    });
};

Route::post('/login/google/callback', FirebaseController::class)->name('login.google.callback');

Route::middleware('auth')->group($routes);

require __DIR__ . '/auth.php';
