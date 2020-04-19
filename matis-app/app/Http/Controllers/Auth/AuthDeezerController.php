<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

use DB;

use App\Services\RandomPseudo\RandomPseudo;

use App\Models\User\User;
use App\Models\User\Deezer;

class AuthDeezerController extends Controller
{
    /**
     * Redirect the user to Deezer connect.
     *
     * @param  \Illuminate\Http\Request $request
     * @return redirect
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

    /**
     * Catch the call from Deezer to authenticate the user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return redirect
     */
	public function callback(Request $request)
	{
		if($request->has("code")) {
			// the 'code' parameter must be provided in the call  
			$responseAccessToken = $this->requestAccessToken($request, $request->get("code"));
			
			if (! isset($responseAccessToken['error'])) {
				// test is the response is well set
				$request->session()->put('accesstokenDeezer', $responseAccessToken["access_token"]);

				$responseUserAccountData = $this->resquestUserAccountData($request, $responseAccessToken["access_token"]);

				if (! isset($responseUserAccountData['error'])) {
					// great, do something ...

					if ($this->isUserExist($responseUserAccountData)) {
						// test if the user has already been logged with Deezer account

						$user = Auth::guard('deezer')->deezer();

						$user->accessToken = $responseAccessToken["access_token"];

						$user->save();

						$request->session()->flash('flash_type', 'alert-success');
						$request->session()->flash('flash_message', '<p><b>Welcome ' . Auth::user()->name .'!</b> You successfully logged in to this website with Deezer and your account data has been updated.</p>');

					} else {
						// new user is coming ...

						//dd($responseUserAccountData, $this->isUserExist($responseUserAccountData), Auth::user(), Auth::guard('deezer')->deezer());

						DB::beginTransaction();

						try {

							$pseudo = RandomPseudo::generate();

							$user = new User;
							$user->name = $pseudo;
							$user->password = Hash::make("mdp" . $pseudo);
							$user->email = $responseUserAccountData['email'];

							$user->save();

							$deezer = new Deezer;
							$deezer->id = $user->id;
							$deezer->deezerId = $responseUserAccountData['id'];
							$deezer->email = $responseUserAccountData['email'];

							$deezer->accessToken = $request->session()->get('accesstokenDeezer');

							$deezer->save();

							DB::commit();

						} catch (\Illuminate\Database\QueryException $e){
							// something went wrong with the transaction, rollback
							DB::rollBack();

							$errorCode = $e->errorInfo[1];

							if($errorCode == 1062){
								$request->session()->flash('flash_type', 'alert-danger');
								$request->session()->flash('flash_message', '<p><b>' . Auth::user()->name .'!</b> We have a duplicate entry problem...<p> <p>Your identifiers have already been used!.</p>');
							} else {
								$request->session()->flash('flash_type', 'alert-danger');
								$request->session()->flash('flash_message', '<p><b>Error!</b> Database error code:'. $e->getMessage() .'</p>');
							}
							return redirect()->route('auth.index');

						} catch (\Exception $e) {
					        // something went wrong elsewhere, handle gracefully
							DB::rollBack();

							$request->session()->flash('flash_type', 'alert-danger');
							$request->session()->flash('flash_message', '<p><b>Error!</b> Database error code:'. $e->getMessage() .'</p>');
							return redirect()->route('auth.index');
					    }			
					}

					if ($this->isUserExist($responseUserAccountData)) {
						// test if the user has already been logged with Deezer account
						$request->session()->flash('flash_type', 'alert-success');
						$request->session()->flash('flash_message', '<p><b>Welcome ' . Auth::user()->name .'!</b> You successfully logged in to this website with Deezer.</p>');

					} else {
						$request->session()->flash('flash_type', 'alert-danger');
						$request->session()->flash('flash_message', '<p><b>Oops...</b> Cannot connect to Deezer!</p>');
					}

				} else {
					$request->session()->flash('flash_type', 'alert-danger');
					$request->session()->flash('flash_message', '<p><b>Error!</b> '. $responseUserAccountData['error'] .'</p>');
				}
			} else {
				$request->session()->flash('flash_type', 'alert-danger');
				$request->session()->flash('flash_message', '<p><b>Error!</b> '. $responseAccessToken['error'] .'</p>');
			}
		} else {
			$request->session()->flash('flash_type', 'alert-danger');
			$request->session()->flash('flash_message', '<p><b>Error!</b> Bad Request</p>');
		}

		return redirect()->route('auth.index');
	}

    /**
     * Save user data on the internal database.
     *
     * @param  \Illuminate\Http\Request $request
     * @return redirect
     */
	public function register(Request $request)
	{
		if (Auth::guard('deezer')->check()) {

			$responseUserAccountData = $this->resquestUserAccountData($request, Auth::guard('deezer')->token());

			if (! isset($responseUserAccountData['error'])) {

				DB::beginTransaction();

				try {

					$deezer = Auth::guard('deezer')->deezer();

					$deezer->deezerId = $responseUserAccountData['id'];
					$deezer->email = $responseUserAccountData['email'];
					$deezer->name = $responseUserAccountData['name'];
					$deezer->firstname = $responseUserAccountData['firstname'];
					$deezer->lastname = $responseUserAccountData['lastname'];
					$deezer->status = $responseUserAccountData['status'];
					$deezer->inscriptionDate = $responseUserAccountData['inscription_date'];
					$deezer->profileLink = $responseUserAccountData['link'];
					$deezer->picture = $responseUserAccountData['picture'];
					$deezer->country = $responseUserAccountData['country'];
					$deezer->lang = $responseUserAccountData['lang'];
					$deezer->isKid = $responseUserAccountData['is_kid'];
					$deezer->tracklist = $responseUserAccountData['tracklist'];
					$deezer->accessToken = $request->session()->get('accesstokenDeezer');

					$deezer->save();

					DB::commit();

					$request->session()->flash('flash_type', 'alert-success');
					$request->session()->flash('flash_message', '<p><b>Welcome ' . Auth::user()->name .'!</b> You account information has been successfully save!</p>');

				} catch (\Illuminate\Database\QueryException $e){
					// something went wrong with the transaction, rollback
					DB::rollBack();

					$errorCode = $e->errorInfo[1];

					if($errorCode == 1062){
						$request->session()->flash('flash_type', 'alert-danger');
						$request->session()->flash('flash_message', '<p><b>' . Auth::user()->name .'!</b> We have a duplicate entry problem...<p> <p>Your identifiers have already been used!.</p>');
					} else {
						$request->session()->flash('flash_type', 'alert-danger');
						$request->session()->flash('flash_message', '<p><b>Error!</b> Database error code:'. $e->getMessage() .'</p>');
					}
					return redirect()->route('auth.index');


				} catch (\Exception $e) {
			        // something went wrong elsewhere, handle gracefully
					DB::rollBack();

					$request->session()->flash('flash_type', 'alert-danger');
					$request->session()->flash('flash_message', '<p><b>Error!</b> Database error code:'. $e->getMessage() .'</p>');
					return redirect()->route('auth.index');
			    }

			} else {
				// either error send by resquestUserAccountData, or by Deezer (if the token has expired for instance...)
				$request->session()->flash('flash_type', 'alert-danger');
				$request->session()->flash('flash_message', '<p><b>Error!</b> '. $responseUserAccountData['error'] .'</p>');
			}
		} else {
			$request->session()->flash('flash_type', 'alert-danger');
			$request->session()->flash('flash_message', '<p><b>Bad Request!</b> You must be connected with Deezer, so that we can save your account data.</p>');
		}

		return redirect()->route('auth.index');
	}

    /**
     * Logout the user from Deezer auth
     *
     * @return redirect
     */
	public function logout() {

		Session::forget('accesstokenDeezer');
		Session::flush();

		Session::flash('flash_type', 'alert-danger');
		Session::flash('flash_message', '<p><b>Done!</b> You have been logout from Deezer.</p>');

		Auth::guard('deezer')->logout();

		return redirect()->route('auth.index');
	}

    /**
     * Get the access token generate by Deezer.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $code
     * @return json
     */
	private function requestAccessToken($request, $code) {

		if(! is_null($code)) {

			$param_url = "https://connect.deezer.com/oauth/access_token.php?";
			$param_app_id = "app_id=" . env('DEEZER_APP_ID');
			$param_secret = "&secret=" . env('DEEZER_SECRET');
			$param_code = "&code=" . $code;

			$url = $param_url . $param_app_id . $param_secret . $param_code . "&output=json";

			$result = Http::get($url);

			if ($result->ok()) {
				return $result->json();

			} else {

				$message = "{'error' => 'Request failed to get access token from Deezer: " . $result->body() . " '}";
				return json_encode($message);
			}
		} else {
			return json_encode(['error' => 'Missing code parameter to get token from Deezer.']);
		}
	}

    /**
     * Get the user account data from Deezer.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string  $accessToken
     * @return json
     */
	private function resquestUserAccountData($request, $accessToken) {
		if(! is_null($accessToken)) {

			$param_url = "https://api.deezer.com/user/me?";
			$param_access_token = '&access_token=' . $accessToken;

			$url = $param_url . $param_access_token . "&output=json";

			$result = Http::get($url);

			if ($result->ok()) {
				return $result->json();

			} else {

				$message = "{'error' => 'Request failed to get access token from Deezer: " . $result->body() . " '}";
				return json_encode($message);
			}
		} else {
			return json_encode(['error' => 'Missing access token to get user data.']);
		}
	}

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $data
     * @return bool
     */
	private function isUserExist($data) {
		$params = [
			'deezerId' => $data["id"],
			'email' => $data["email"]
		];

		return Auth::guard('deezer')->attempt($params);
	}
}
