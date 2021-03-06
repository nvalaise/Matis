<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BoardDeezerController extends Controller
{

    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('isDeezer');
    }

	public function account() {

		$token = $this->getToken();

		$param_url = "https://api.deezer.com/user/me?";
		$param_access_token = '&access_token=' . $token;

		$url = $param_url . $param_access_token . "&output=json";

		$response = Http::get($url)->json();

		return response()->json([
			'response' => $response,
			'access_token' => $token
		]);
	}

	public function playlists($start = null) {

		$index = empty($start) ? 0 : $start;

		$baseURL = "https://api.deezer.com/user/me/playlists&limit=10&index=" . $index;
		$playlistsURL = $baseURL . $this->getTokenAsParameter();

		return Http::get($playlistsURL)->json();

	}

	public function playlist($id, $start = null) {

		$index = empty($start) ? 0 : $start;

		$baseURL = "https://api.deezer.com/playlist/" . $id . "/tracks&limit=20&index=" . $index;
		$playlistsURL = $baseURL . $this->getTokenAsParameter();

		return Http::get($playlistsURL)->json();

	}

	public function artists($start = null) {
		
		$index = empty($start) ? 0 : $start;

		$baseURL = "https://api.deezer.com/user/me/artists&limit=15&index=" . $index;
		$playlistsURL = $baseURL . $this->getTokenAsParameter();

		return Http::get($playlistsURL)->json();

	}

	public function artist($id) {

		$index = empty($start) ? 0 : $start;

		$baseURL = "https://api.deezer.com/artist/" . $id . "/top";
		$playlistsURL = $baseURL . $this->getTokenAsParameter();

		return Http::get($playlistsURL)->json();

	}



	public function history() {

		$baseURL = "https://api.deezer.com/user/me/history";
		$playlistsURL = $baseURL . $this->getTokenAsParameter();

		$response = Http::get($playlistsURL)->json();

        $historyChart = array();
        $max = 0;

		if (isset($response['data'])) {

			$mapHistory = array();

			foreach ($response['data'] as $history) {

				$date = date("Y-m-d", $history['timestamp']);
				if(isset($mapHistory[$date])) {
					$mapHistory[$date] = $mapHistory[$date]+1;
				} else {
					$mapHistory[$date] = 1;
				}
			}

			foreach ($mapHistory as $key => $value) {
				$historyChart[] = array(
					'date' => $key,
					'count' => $value
				);
				if($value > $max) { $max = $value; }
			}
		}

		return response()->json([
				'max' => $max,
				'history' => json_encode($historyChart),
				'response' => $response
			],200);
	}

	public function social() {

		$baseURL = "https://api.deezer.com/user/me/followings";
		$playlistsURL = $baseURL . $this->getTokenAsParameter();

		return Http::get($playlistsURL)->json();

	}

	private function getToken() {
		return Auth::user()->access_token;
	}

	private function getTokenAsParameter() {
		if (($token = $this->getToken()) !== null) {
			return '&access_token=' . $token;
		}
	}
}
