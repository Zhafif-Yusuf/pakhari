<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    HomeController,
    AlbumController,
    PhotoController,
    ProfileController,
};

// Redirect root URL to login
Route::redirect('/', '/auth/login');


// Authentication routes
Route::controller(AuthController::class)->group(function () {
    Route::get('auth/login', 'showLoginForm')->name('login')->middleware('guest');
    Route::post('auth/postLogin', 'postLogin')->name('postLogin');
    Route::post('logout', 'logout')->name('logout');
    Route::get('auth/register', 'showRegistrationForm')->name('register.form');
    Route::post('auth/register', 'register')->name('register');
});
// Routes that require authentication
Route::middleware('auth')->group(function () {
    // Home route
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Album resource routes
    Route::resource('albums', AlbumController::class);
    Route::get('albums/{album}/photos', [PhotoController::class, 'index'])
    ->name('albums.photos');

    // Photo resource routes
Route::resource('photos', PhotoController::class);

// Route to display photos in a specific album
Route::get('/albums/{album}/photos', [PhotoController::class, 'index'])
    ->name('albums.photos');

// Route for liking a photo
Route::post('photos/{photo}/like', [PhotoController::class, 'like'])
    ->name('photos.like');

// Route to show comments for a photo
Route::get('/photos/{photo}/comments', [PhotoController::class, 'showComments'])
    ->name('photos.comments');

// Route to store a new comment on a photo
Route::post('/photos/{photo}/comments', [PhotoController::class, 'storeComment'])
    ->name('photos.comment.store');
// Profile routes
Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});
