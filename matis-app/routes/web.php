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

Route::prefix('auth')->group(function () {

	Route::get('/', function () {

		return view('app.auth');
	})->name('auth.index');

	Route::prefix('deezer')->group(function () {

		Route::get('/login', 'Auth\AuthDeezerController@login')->name('auth.deezer.login');
		Route::get('/callback', 'Auth\AuthDeezerController@callback')->name('auth.deezer.callback');
		
		Route::get('/register', 'Auth\AuthDeezerController@register')->name('auth.deezer.register');

		Route::get('/logout', 'Auth\AuthDeezerController@logout')->name('auth.deezer.logout');
	});

});

// Route to handle page reload in Vue except for api routes
Route::get('/deezer/{any?}', function () {
    return view('app.deezer');
})->where('any', '^(?!api\/)[\/\w\.-]*');



//Auth::routes();
