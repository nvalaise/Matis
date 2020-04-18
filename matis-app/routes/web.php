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
		
		Route::get('/create', 'Auth\AuthDeezerController@create')->name('auth.deezer.create');

		Route::get('/logout', 'Auth\AuthDeezerController@logout')->name('auth.deezer.logout');
	});

});




//Auth::routes();
