<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
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

		//dd(Auth::guard('deezer'));
		//dd(Auth::user(), Auth::guard('deezer'), Auth::guard('deezer')->check(), Session::get('accesstokenDeezer'));

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
