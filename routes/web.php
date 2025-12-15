<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/sso', [App\Http\Controllers\SsoController::class, 'ssologin'])->name('sso-login');
Route::get('/post-sso', [App\Http\Controllers\SsoController::class, 'postssologin'])->name('post-sso-login');

// Route::get('/', function () {
//     return view('welcome');
// });