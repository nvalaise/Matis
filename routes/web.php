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

Route::get('/', 'ViewController@home')->name('home');

// => /auth/...
Route::prefix('auth')->group(function () {

	// => /auth
	Route::get('/', 'ViewController@auth')->name('auth.index');
	
	Route::group(['prefix' => '{driver}'], function () {

		Route::get('/login', 'LoginController@redirectToProvider')->name('auth.login');
		Route::get('/callback', 'LoginController@handleProviderCallback')->name('auth.callback');		
	});
	Route::get('/logout', 'LoginController@logout')->name('auth.logout');

});

// Route to handle page reload in Vue except for api routes
Route::get('/deezer/{any?}', 'ViewController@deezer')
	->where('any', '^(?!api\/)[\/\w\.-]*')
	->name('deezer.vue');

Route::get('/spotify/{any?}', 'ViewController@spotify')
	->where('any', '^(?!api\/)[\/\w\.-]*')
	->name('spotify.vue');
