<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Deezer;

class AuthDeezerController extends Controller
{
	/**
     * Public
     */
	public function login(Request $request)
	{
		$baseURL = "https://connect.deezer.com/oauth/auth.php?";
		$app_id = "app_id=" . env('DEEZER_APP_ID');
		$redirect_uri = "&redirect_uri=" . env("APP_URL") . "/auth/deezer/callback";
		$perms = "&perms=basic_access,email,manage_library,listening_history";

		$connectURL = $baseURL . $app_id . $redirect_uri . $perms ;

		return redirect()->to($connectURL);
	}

	public function callback(Request $request)
	{
		$response = $this->getDeezerToken($request);
		$data1 = $response->json();

		if ($response->successful() && isset($data1)) {

			if (isset($data1["access_token"])) {
				$request->session()->put('accesstokenDeezer', $data1["access_token"]);

				$response = $this->getDeezerMe($request);
				$data2 = $response->json();

				if (isset($data2["error"])) {
					$request->session()->forget('accesstokenDeezer');
					abort(400, 'Request failed to get the token from Deezer: ' . $data2["error"]);
				} else {

					if ($this->userExist($data2)) {

						$user = Auth::guard('deezer')->user();

						$user->deezerId = $data2['id'];
						$user->email = $data2['email'];
						$user->name = $data2['name'];
						$user->firstname = $data2['firstname'];
						$user->lastname = $data2['lastname'];
						$user->status = $data2['status'];
						$user->inscriptionDate = $data2['inscription_date'];
						$user->profileLink = $data2['link'];
						$user->picture = $data2['picture'];
						$user->country = $data2['country'];
						$user->lang = $data2['lang'];
						$user->accessToken = $data1["access_token"];

						$user->save();

						$request->session()->flash('flash_type', 'alert-success');
						$request->session()->flash('flash_message', '<p><b>Welcome ' . $data2["name"] .'!</b> You successfully logged in to this website with Deezer and your account data has been updated.</p>');

					} else {

						$request->session()->flash('flash_type', 'alert-info');
						$request->session()->flash('flash_message', '<p><b>Welcome ' . $data2["name"] .'!</b> You successfully logged in to this website with Deezer.</p><p>It seems that is it the first time you are connected with Deezer. Click on the following link it you want to save your account data localy: <a href="' . route('auth.deezer.create') . '"">Save localy</a><p>');
					}

					return redirect()->route('auth.index');
                    //return view('auth.deezer');
				}

			} else {
				abort(400, 'Unable to get the token from Deezer.');
			}

		} else {
			abort(400, 'Request failed to get the token from Deezer: ' . $response->body());
		};
	}

	public function create(Request $request)
	{
		$response = $this->getDeezerMe($request);
		$data = $response->json();


		if (isset($data["error"])) {
			$request->session()->forget('accesstokenDeezer');
			abort(400, 'Request failed to get the token from Deezer: ' . $data["error"]);
		} else {

			try {

				Deezer::create([
					'deezerId' => $data['id'],
					'email' => $data['email'],
					'name' => $data['name'],
					'firstname' => $data['firstname'],
					'lastname' => $data['lastname'],
					'status' => $data['status'],
					'inscriptionDate' => $data['inscription_date'],
					'profileLink' => $data['link'],
					'picture' => $data['picture'],
					'country' => $data['country'],
					'lang' => $data['lang'],
					'isKid' => $data['is_kid'],
					'tracklist' => $data['tracklist'],
					'deezer_accessToken' => $request->session()->get('accesstokenDeezer')
				]);
			}
			catch (\Illuminate\Database\QueryException $e){
				$errorCode = $e->errorInfo[1];

				if($errorCode == 1062){
					$request->session()->flash('flash_type', 'alert-danger');
					$request->session()->flash('flash_message', '<p><b>' . $data["name"] .'!</b> We have a duplicate entry problem...<p> <p>You have already saved your data.</p>');
				} else {
					$request->session()->flash('flash_type', 'alert-danger');
					$request->session()->flash('flash_message', '<p><b>Jackson!</b> Database error code:'. $e .'</p>');
				}
			}

			if($this->userExist($data)) {
				$request->session()->flash('flash_type', 'alert-success');
				$request->session()->flash('flash_message', '<p><b>Welcome ' . $data2["name"] .'!</b> You successfully logged in to this website with Deezer and your account data has been updated.</p>');
			} else {
				$request->session()->flash('flash_type', 'alert-danger');
				$request->session()->flash('flash_message', '<p><b>Done!</b> Cannot connect the user to the Deezer account.</p>');
			}

			return redirect()->route('auth.index');
		}
	}

	public function logout() {

		Session::forget('accesstokenDeezer');
		Session::flush();

		Session::flash('flash_type', 'alert-danger');
		Session::flash('flash_message', '<p><b>Done!</b> You have been logout from Deezer.</p>');

		Auth::logout();

		return redirect()->route('auth.index');
	}

	private function getDeezerToken($request) {
		if($request->has("code")) {

			$baseURL = "https://connect.deezer.com/oauth/access_token.php?";
			$app_id = "app_id=" . env('DEEZER_APP_ID');
			$secretApp = "&secret=" . env('DEEZER_SECRET');
			$code = "&code=" . $request->input("code");

			$tokenURL = $baseURL . $app_id . $secretApp . $code . "&output=json";

			return Http::get($tokenURL);
		} else {
			abort(400, 'Missing code parameter to get token from Deezer.');
		}
	}

	private function getDeezerMe($request) {
		if($this->hasAccessToken($request)) {

			$baseURL = "https://api.deezer.com/user/me?";

			$token = '&access_token=' . $request->session()->get('accesstokenDeezer');

			$meURL = $baseURL . $token . "&output=json";

			return Http::get($meURL);
		} else {
			abort(400, 'Missing access token to get user data.');
		}
	}

	private function hasAccessToken($request) {
		return $request->session()->has('accesstokenDeezer');
	}

	private function userExist($data) {
		$params = [
			'deezerId' => $data["id"],
			'email' => $data["email"]
		];

		return Auth::guard('deezer')->attempt($params);
	}
}