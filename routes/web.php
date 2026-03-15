<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ThreadLikeController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');

    Route::get('/threads/create', [ThreadController::class, 'create'])->name('threads.create');
    Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');
    Route::get('/threads/{thread}', [ThreadController::class, 'show'])->name('threads.show');

    Route::get('/majors', [MajorController::class, 'index'])->name('majors.index');
    Route::get('/majors/{major}', [MajorController::class, 'show'])->name('majors.show');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('/threads/{thread}/replies', [ReplyController::class, 'store'])
    ->middleware('auth')
    ->name('replies.store');
Route::post('/threads/{thread}/like', [ThreadLikeController::class, 'store'])
    ->middleware('auth')
    ->name('threads.like');
