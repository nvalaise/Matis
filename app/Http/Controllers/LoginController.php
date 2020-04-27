<?php

namespace App\Http\Controllers;


use Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Services\RandomPseudo\RandomPseudo;
use App\Models\User;
use App\Models\UserAccount;


use DB;

class LoginController extends Controller
{
	protected $providers = [
        'deezer', 'spotify', 'discogs'
    ];

	public function redirectToProvider($driver)
	{
		if( ! $this->isProviderAllowed($driver) ) {
			return $this->sendFailedResponse("{$driver} is not currently supported");
		}

		try {
			return Socialite::with($driver)->stateless(false)->redirect();
		} catch (Exception $e) {
			return $this->sendFailedResponse($e->getMessage());
		}
	}

	public function handleProviderCallback($driver)
	{
		try {			
			$user = Socialite::driver($driver)->stateless(false)->user();
		} catch (Exception $e) {
			return $this->sendFailedResponse($e->getMessage());
		}

		return $this->loginOrCreateAccount($user, $driver);
	}

	protected function loginOrCreateAccount($providerUser, $driver)
	{
		// 4-eye check to validate the driver
		if( ! $this->isProviderAllowed($driver) ) {
			return $this->sendFailedResponse("{$driver} is not currently supported");
		}

		// check for already has account
		$user = User::where('provider', $driver)
			->where('provider_id', $providerUser->id)
			->first();

		// if user already found
		if( $user ) {
			
			$user->update([
				'provider' => $driver,
				'provider_id' => $providerUser->id,
				'access_token' => $providerUser->token
			]);
			$user->save();

			if(! ($user_account = $user->has($driver))) {
				switch ($driver) {
					case 'deezer':
						$user_account->deezer_id = $user->id;
						break;
					
					default:
						break;
				}

				$user_account->save();
			}
		} else {
			DB::beginTransaction();

			try {
				// create a new user
				$pseudo = RandomPseudo::generate();

				$user = User::create([
					'provider' => $driver,
					'provider_id' => $providerUser->id,
					'email' => $providerUser->email,
					'password' => Hash::make("mdp" . $pseudo), // user can use reset password to create a password					
					'access_token' => $providerUser->token,
				]);
				$user->save();

				$user_account = UserAccount::create([
					'pseudo' => RandomPseudo::generate(),
					$driver . '_id' => $user->id
				]);
				$user_account->save();
	
				DB::commit();

			} catch (\Exception $e) {
				// something went wrong elsewhere, handle gracefully
				DB::rollBack();

				Session::flash('flash_type', 'alert-danger');
				Session::flash('flash_message', '<p><b>Error!</b> Database error code:'. $e->getMessage() .'</p>');
				
				return redirect()->route('auth.index');
			}			
		}

		Auth::loginUsingId($user->id);

		if(Auth::check()) {
			Session::flash('flash_type', 'alert-success');
			Session::flash('flash_message', '<p><b>Welcome !</b> You successfully logged in to this website with Deezer.</p>');
		} else {
			Session::flash('flash_type', 'alert-danger');
			Session::flash('flash_message', '<p><b>Oups !</b> Something went wrong....</p>');
		}

		return redirect()->route('auth.index');
	}

	public function logout() {

		if(Auth::check()) {
			Auth::logout();
			Session::flash('flash_type', 'alert-warning');
			Session::flash('flash_message', '<p><b>Done !</b> You have been logout.</p>');
		} else {
			Session::flash('flash_type', 'alert-danger');
			Session::flash('flash_message', '<p><b>Oups !</b> Something went wrong....</p>');
		}

		return redirect()->route('auth.index');
	}

	private function isProviderAllowed($driver)
	{
		return in_array($driver, $this->providers) && config()->has("services.{$driver}");
	}
}
