<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BoardSpotifyController extends Controller
{

    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('isSpotify');
    }

    protected function headers() {
		$token = $this->getToken();
    	return [
			'Authorization' => 'Bearer ' . $token,        
			'Accept'        => 'application/json',
		];
    }
    
	public function account() {

		$url = "https://api.spotify.com/v1/me";
		$response = Http::withHeaders($this->headers())
			->get($url)
			->json();

		return response()->json([
			'response' => $response,
			'access_token' => $this->getToken()
		]);
	}

	public function playlists($start = null) {

		$index = empty($start) ? 0 : $start;
		$url = "https://api.spotify.com/v1/me/playlists?limit=10&offset=" . $index;
		
		return Http::withHeaders($this->headers())
			->get($url)
			->json();
	}

	public function playlist($id, $start = null) {

		$index = empty($start) ? 0 : $start;
		$url = "https://api.spotify.com/v1/playlists/" . $id . "/tracks?limit=20&offset=" . $index;
		
		return Http::withHeaders($this->headers())
			->get($url)
			->json();
	}

	public function artists($start = null) {
		
		$index = empty($start) ? 0 : $start;
		$url = "https://api.spotify.com/v1/me/following?type=artist&limit=20&offset=" . $index;

		return Http::withHeaders($this->headers())
			->get($url)
			->json();
	}

	public function artist($id) {

		$index = empty($start) ? 0 : $start;
		$url = "https://api.spotify.com/v1/artists/" . $id . "/top-tracks?country=from_token";

		return Http::withHeaders($this->headers())
			->get($url)
			->json();
	}



	public function history() {

		$url = "https://api.spotify.com/v1/me/player/recently-played?limit=50";

		$response = Http::withHeaders($this->headers())
			->get($url)
			->json();

        $historyChart = array();
        $max = 0;

		if (isset($response['items'])) {

			$mapHistory = array();

			foreach ($response['items'] as $history) {

				$date = substr($history['played_at'], 0, 10);
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

		$url = "https://api.spotify.com/v1/me/following?type=artist";
		return Http::withHeaders($this->headers())
			->get($url)
			->json();
	}

	private function getToken() {
		return Auth::user()->access_token;
	}
}
