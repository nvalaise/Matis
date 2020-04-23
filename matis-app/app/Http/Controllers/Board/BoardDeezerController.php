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

	public function account() {

		if ($this->guard()->check()) {

			return $this->guard()->deezer();
		} else {
			return response()->json(
				['message' => 'Access token not found'], 
				400);
		}
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

	public function playlist($id, $start = null) {

		if ($this->guard()->check()) {

			$index = empty($start) ? 0 : $start;

			$baseURL = "https://api.deezer.com/playlist/" . $id . "/tracks&limit=20&index=" . $index;
			$playlistsURL = $baseURL . $this->getTokenAsParameter();

			return Http::get($playlistsURL)->json();
		} else {
			return response()->json(
				['message' => 'Access token not found'], 
				400);
		}
	}



	public function history() {

		if ($this->guard()->check()) {

			$baseURL = "https://api.deezer.com/user/me/history";
			$playlistsURL = $baseURL . $this->getTokenAsParameter();

			$response = Http::get($playlistsURL)->json();

			//dd($response);

/*
            this.historyValues = [{ 
                date: '2020-3-20', count: 5 },{
                date: '2020-3-21', count: 11 },{
                date: '2020-3-23', count: 2 },{
                date: '2020-3-24', count: 6 
            }];
*/
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
				//dd($mapHistory, $historyChart);

			}

			return response()->json([
					'max' => $max,
					'history' => json_encode($historyChart),
					'response' => $response
				],200);
		} else {
			return response()->json(
				['message' => 'Access token not found'], 
				400);
		}
	}

	public function social() {

		if ($this->guard()->check()) {

			$baseURL = "https://api.deezer.com/user/me/followings";
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
