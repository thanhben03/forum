<?php


use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->name('v1.')->group(function () {

    Route::get('/register', [AuthController::class, 'test'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});
