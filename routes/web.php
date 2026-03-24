<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentDislikeController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ThreadDislikeController;
use App\Http\Controllers\ThreadLikeController;
use Illuminate\Support\Facades\Route;

//Route::view('/', 'home')->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Subjects
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');

    // Threads
    Route::get('/threads/create', [ThreadController::class, 'create'])->name('threads.create');
    Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');
    Route::get('/threads/{thread}', [ThreadController::class, 'show'])->name('threads.show');

    // Comments
    Route::post('/threads/{thread}/comments', [CommentsController::class, 'store'])
        ->name('comments.store');

    Route::post('/comments/{comment}/reply', [CommentsController::class, 'reply'])
        ->name('comments.reply');

    Route::put('/comments/{comment}', [CommentsController::class, 'update'])
        ->name('comments.update');

    Route::delete('/comments/{comment}', [CommentsController::class, 'destroy'])
        ->name('comments.destroy');
    Route::post('/comments/{comment}/like', [CommentLikeController::class, 'toggle'])
        ->name('comments.like');

    // Thread likes
    Route::post('/threads/{thread}/like', [ThreadLikeController::class, 'store'])
        ->name('threads.like');

    Route::get('/threads/{thread}/edit', [ThreadController::class, 'edit'])->name('threads.edit');
    Route::put('/threads/{thread}', [ThreadController::class, 'update'])->name('threads.update');
    Route::delete('/threads/{thread}', [ThreadController::class, 'destroy'])->name('threads.destroy');

    Route::post('/threads/{thread}/dislike', [ThreadDislikeController::class, 'toggle'])
        ->name('threads.dislike');

    Route::post('/comments/{comment}/dislike', [CommentDislikeController::class, 'toggle'])
        ->name('comments.dislike');
    // Majors
    Route::get('/majors', [MajorController::class, 'index'])->name('majors.index');
    Route::get('/majors/{major}', [MajorController::class, 'show'])->name('majors.show');

    // Semesters
    Route::get('/semesters', [SemesterController::class, 'index'])->name('semesters.index');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


