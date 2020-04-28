<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

    return view('app.home');
});

// => /auth/...
Route::prefix('auth')->group(function () {

	// => /auth
	Route::get('/', function () {

		return view('app.auth');
	})->name('auth.index');
	
	Route::group(['prefix' => '{driver}'], function () {

		Route::get('/login', 'LoginController@redirectToProvider')->name('auth.login');
		Route::get('/callback', 'LoginController@handleProviderCallback')->name('auth.callback');
		
		Route::get('/logout', 'LoginController@logout')->name('auth.logout');
	});
});

// Route to handle page reload in Vue except for api routes
Route::get('/deezer/{any?}', function () {
	dd(Auth::user());	

    return view('app.deezer');
})->where('any', '^(?!api\/)[\/\w\.-]*')->middleware('switchAccount')->name('deezer.vue');;

Route::get('/spotify/{any?}', function () {
	dd(Auth::user());
	
    // return view('app.spotify');
})->where('any', '^(?!api\/)[\/\w\.-]*')->middleware('switchAccount')->name('spotify.vue');;
