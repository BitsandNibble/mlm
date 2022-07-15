<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\SocialController;

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

Route::get('/', function () {
	return view('welcome');
});

// Admin route
Route::group(['prefix' => 'admin', 'name' => 'admin.', 'middleware' => ['auth', 'isAdmin']], function () {
	Route::view('dashboard', 'admin.dashboard')->name('dashboard');
});

// User route
Route::view('dashboard', 'dashboard')->name('dashboard');


require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
	Route::view('about', 'about')->name('about');

	Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');

	Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
	Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Route::get('auth/{redirect}', [SocialController::class, 'redirect'])->name('social.login');
Route::get('auth/callback/facebook', [SocialController::class, 'loginWithFacebook']);
Route::get('auth/callback/google', [SocialController::class, 'loginWithGoogle']);
