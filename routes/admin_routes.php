<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\ContactController;


Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.home')->name('home');

    Route::post('/', [LoginController::class, 'logout'])->name('logout');
    Route::resources([
        'users' => UserController::class,
        'books' => BookController::class,
        'authors' => AuthorController::class,
        'contacts' => ContactController::class
    ]);
    Route::get('/usersSearch', [UserController::class, 'search'])->name('users.search');
    Route::get('/booksSearch', [BookController::class, 'search'])->name('books.search');
    Route::get('/authorsSearch', [AuthorController::class, 'search'])->name('authors.search');
    Route::get('/contactsSearch', [ContactController::class, 'search'])->name('contacts.search');
});
