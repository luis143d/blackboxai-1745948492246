<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware('auth')->group(function () {
    Route::get('/chat', function () {
        return view('chat');
    })->name('chat');

    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});
