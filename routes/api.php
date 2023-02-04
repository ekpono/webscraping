<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ScraperController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('web');
Route::get('/logout', [LoginController::class, 'logout'])->middleware('web');


//Books
Route::resource('/books', ScraperController::class)->middleware('web');
