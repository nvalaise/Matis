<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;

use Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BoardDiscogsController extends Controller
{

    public function __construct()
    {
        $this->middleware('web');
        // $this->middleware('isSpotify');
    }

    protected function headers() {
    	$dateTime = new \DateTime();

    	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $nonce = substr(str_shuffle(str_repeat($pool, 5)), 0, 32);

		$parameters = array(
		    'oauth_consumer_key' =>  config('services.discogs.identifier'),
		    'oauth_nonce' => $nonce,
		    'oauth_signature_method' => 'HMAC-SHA1',
		    'oauth_timestamp' => $dateTime->format('U'),
		    'oauth_version' => '1.0',
			'oauth_token' => $this->getToken()
		);

        array_walk($parameters, function (&$value, $key) {
            $value = rawurlencode($key).'="'.rawurlencode($value).'"';
        });

        return array(
        	'Authorization' => 'OAuth '.implode(', ', $parameters)
    	);
    }

	protected function getUsername() {
		return Auth::user()->username;
	}

	protected function getToken() {
		return Auth::user()->access_token;
	}

	public function account() {
		
        $url = 'https://api.discogs.com/users/'.$this->getUsername();
        $headers = $this->headers();

		return Http::withHeaders($headers)
			->get($url)
			->json();
	}

	public function collection($start = null) {

		$index = empty($start) ? 0 : $start;
		$url = "https://api.spotify.com/v1/me/playlists?limit=10&offset=" . $index;
		
		return Http::withHeaders($this->headers())
			->get($url)
			->json();
	}

	public function item($id, $start = null) {

		$index = empty($start) ? 0 : $start;
		$url = "https://api.spotify.com/v1/playlists/" . $id . "/tracks?limit=20&offset=" . $index;
		
		return Http::withHeaders($this->headers())
			->get($url)
			->json();
	}

	public function wishlist($start = null) {
		
		$index = empty($start) ? 0 : $start;
		$url = "https://api.spotify.com/v1/me/following?type=artist&limit=20&offset=" . $index;

		return Http::withHeaders($this->headers())
			->get($url)
			->json();
	}
}
