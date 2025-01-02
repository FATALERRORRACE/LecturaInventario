<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register',  [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'registerPost']);
    Route::get('/login',     [AuthController::class, 'login'])->name('login');
    Route::post('/login',    [AuthController::class, 'loginPost']);
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/',         [IndexController::class, 'index']);
    Route::get('/logout',   [AuthController::class, 'logout'])->name('logout');
});