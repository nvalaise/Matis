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

Route::prefix('deezer')->group(function () {
	Route::get('/account', 'Board\BoardDeezerController@account')->name('deezer.account');
	Route::get('/history', 'Board\BoardDeezerController@history')->name('deezer.history');
	Route::get('/social', 'Board\BoardDeezerController@social')->name('deezer.social');

	Route::get('/playlists', 'Board\BoardDeezerController@playlists')->name('deezer.playlists');
	Route::get('/playlist/{id}/{start?}', 'Board\BoardDeezerController@playlist')
		->name('deezer.playlist')
		->where('id', '[0-9]+')
		->where('start', '[0-9]+');

	Route::get('/artists/{start?}', 'Board\BoardDeezerController@artists')
		->name('deezer.artists')
		->where('start', '[0-9]+');

	Route::get('/artist/{id}', 'Board\BoardDeezerController@artist')
		->name('deezer.artist')
		->where('id', '[0-9]+');
});
