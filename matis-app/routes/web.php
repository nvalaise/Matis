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
		
		//Route::get('/logout', 'LoginController@logout')->name('auth.logout');
	});

	// => /auth/deezer/...
	/*Route::prefix('deezer')->group(function () {

		Route::get('/login', 'Auth\AuthDeezerController@login')->name('auth.deezer.login');
		Route::get('/callback', 'Auth\AuthDeezerController@callback')->name('auth.deezer.callback');
		
		Route::get('/register', 'Auth\AuthDeezerController@register')->name('auth.deezer.register');

		Route::get('/logout', 'Auth\AuthDeezerController@logout')->name('auth.deezer.logout');
	});*/

});

// Route to handle page reload in Vue except for api routes
Route::get('/deezer/{any?}', function () {
    return view('app.deezer');
})->where('any', '^(?!api\/)[\/\w\.-]*')->name('deezer.vue');;

Route::group(['prefix' => 'api/deezer',  'middleware' => 'isDeezer'], function() {
	Route::get('/account', 'Board\BoardDeezerController@account')->name('deezer.account');
	Route::get('/playlists', 'Board\BoardDeezerController@playlists')->name('deezer.playlists');
	Route::get('/history', 'Board\BoardDeezerController@history')->name('deezer.history');
	Route::get('/social', 'Board\BoardDeezerController@social')->name('deezer.social');

	Route::get('/playlist/{id}/{start?}', 'Board\BoardDeezerController@playlist')
		->name('deezer.playlist')
		->where('id', '[0-9]+')
		->where('start', '[0-9]+');
});
