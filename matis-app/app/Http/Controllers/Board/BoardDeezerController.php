<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BoardDeezerController extends Controller
{
    protected function guard()
	{
	    return Auth::guard('deezer');
	}

	public function playlists() {

		if ($this->guard()->check()) {

			$baseURL = "https://api.deezer.com/user/me/playlists";
			$playlistsURL = $baseURL . $this->getTokenAsParameter();

			return Http::get($playlistsURL)->json();
		} else {
			return response()->json(
				['message' => 'Access token not found'], 
				400);
		}
	}

	private function getToken() {
		return $this->guard()->token();
	}

	private function getTokenAsParameter() {
		if (($token = $this->getToken()) !== null) {
			return '&access_token=' . $token;
		}
	}
}
