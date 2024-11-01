<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\LevelController;

//Login
Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Logout
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

// Level
Route:: get('levels', [LevelController::class, 'index' ]);
Route::post('levels', [LevelController::class, 'store' ]);
Route:: get('levels/{level}', [LevelController::class, 'show' ]);
Route:: put('levels/{level}', [LevelController::class, 'update' ]);
Route::delete('levels/{level}', [LevelController::class, 'destroy' ]);