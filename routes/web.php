<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('register', [App\Http\Controllers\User\AuthController::class, 'registerForm'])->name('register.form');
Route::get('login', [App\Http\Controllers\User\AuthController::class, 'loginForm'])->name('login.form');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'home']);


Route::get('/posts/{id}', [App\Http\Controllers\HomeController::class, 'detail_post'])->name('post.detail_post');
Route::get('/posts/{id}/edit', [App\Http\Controllers\HomeController::class, 'edit_post'])->name('post.form_edit');

Route::get('', function () {
    return redirect('/login');
});
