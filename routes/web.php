<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
Route::get('/', [IndexController::class, 'index'])->middleware(['auth'])->name('index');

 
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register',  [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register');
    Route::get('/login',     [AuthController::class, 'login'])->name('login');
    Route::post('/login',    [AuthController::class, 'loginPost'])->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/',          [IndexController::class, 'index']);
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
});