<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'jwt.auth'], function () {
	
	// Auth routes
	Route::get('/user', [AuthController::class, 'user'])->name('auth.user.data');
    Route::post('/token-refresh', [AuthController::class, 'refresh'])->name('token-refresh');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User routes
    Route::group(['middleware' => ['role:User']], function () {
		Route::put('user/update', [UserController::class, 'update'])->name('user.update');
		Route::put('user/change-password', [UserController::class, 'changePassword'])->name('user.change-password');
	});

    // Admin routes
    Route::group(['middleware' => ['role:Admin']], function () {
		Route::get('/user/list', [AdminController::class, 'listUser'])->name('admin.user.list');
		Route::put('/user/{id}', [AdminController::class, 'updateUser'])->name('admin.user.update');
		Route::delete('/user/{id}', [AdminController::class, 'destroyUser'])->name('admin.user.destroy');
	});
});