<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Auth::routes();

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::softDeletes('users', UserController::class);
    Route::resource('users', UserController::class);
});