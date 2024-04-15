<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('auth/login', [App\Http\Controllers\User\AuthController::class, 'login'])->name('login');
Route::post('/register', [App\Http\Controllers\User\AuthController::class, 'register'])->name('register');
Route::get('/home', [App\Http\Controllers\User\AuthController::class, 'list_post'])->name('post.index_post');
Route::get('/post/{id}', [App\Http\Controllers\User\AuthController::class, 'detail_post_user'])->name('post.detail');


Route::group(['middleware' => 'auth:api'], function () {
    Route::post('auth/logout', [App\Http\Controllers\User\AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [App\Http\Controllers\User\AuthController::class, 'me'])->name('me');
    Route::post('/update', [App\Http\Controllers\User\AuthController::class, 'update_post'])->name('post.update_form');
});
