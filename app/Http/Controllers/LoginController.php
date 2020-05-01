<?php

namespace App\Http\Controllers;


use Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

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
			Session::put('__redirect_url', URL::previous());
			return Socialite::with($driver)->redirect();
			//return Socialite::with($driver)->stateless(false)->redirect();
		} catch (Exception $e) {
			return $this->sendFailedResponse($e->getMessage());
		}
	}

	public function handleProviderCallback($driver)
	{
		try {			
			$user = Socialite::driver($driver)->user();
			//$user = Socialite::driver($driver)->stateless(false)->user();
		} catch (Exception $e) {
			return $this->sendFailedResponse($e->getMessage());
		}

		return $this->loginOrCreateAccount($user, $driver);
	}

	protected function loginOrCreateAccount($providerUser, $driver)
	{
		// 4-eye to validate the driver
		if( ! $this->isProviderAllowed($driver) ) {
			return $this->sendFailedResponse("{$driver} is not currently supported");
		}

		$authUser = Auth::user();

		// check if user already has account with this provider
		$userFromProvider = User::where('provider', $driver)
			->where('provider_id', $providerUser->id)
			->first();

		/////
		///// 1. first connection with this provider and not authenticated
		/////
		if ( is_null($userFromProvider) && is_null($authUser)) {
			// if the mail already exist from another provider, stop the actions so don't create the accounts reference
			$userFromEmail = User::where('email', $providerUser->email)->first();
			if(! is_null($userFromEmail)) {
				Session::flash('flash_type', 'alert-danger');
				Session::flash('flash_message', '<p><b>Error!</b> Your email have been alredy used with <b>'.ucfirst($userFromEmail->provider).'</b> for example.<br> Please get connected with this application, then you will be able to add your <b>'.ucfirst($driver).'</b> account.</p>');
				return redirect()->route('auth.index');
			}

			// create the user and create the accounts reference
			$pseudo = RandomPseudo::generate();
			DB::beginTransaction();
			try {
				$authUser = User::create([
					'provider' => $driver,
					'provider_id' => $providerUser->id,
					'email' => $providerUser->email,
					'password' => Hash::make("mdp" . $pseudo), // user can use reset password to create a password					
					'access_token' => $providerUser->token,
				]);
				$authUser->save();
				$new_userAccount = UserAccount::create([
					'pseudo' => RandomPseudo::generate(),
					$driver . '_id' => $authUser->id
				]);
				$new_userAccount->save();
				DB::commit();
			} catch (\Exception $e) {
				// something went wrong elsewhere, handle gracefully
				DB::rollBack();
				Session::flash('flash_type', 'alert-danger');
				Session::flash('flash_message', '<p><b>Error!</b> Database error code:'. $e->getMessage() .'</p>');
				return redirect()->route('auth.index');
			}
			Session::flash('flash_type', 'alert-success');
			Session::flash('flash_message', '<p><b>Welcome '.$pseudo.'!</b> Hope you will enjoy Matis.</p>');
			Auth::loginUsingId($authUser->id);
			return $this->redirectPreviousOrView();
		}

		/////
		///// 2. first connection with this provider and authenticated
		/////
		if ( is_null($userFromProvider) && ! is_null($authUser)) {
			// create the user and link it to its accounts
			$pseudo = $authUser->pseudo();
			if(is_null($pseudo)) { $pseudo = RandomPseudo::generate(); }
			DB::beginTransaction();
			try {
				$user = User::create([
					'provider' => $driver,
					'provider_id' => $providerUser->id,
					'email' => $providerUser->email,
					'password' => Hash::make("mdp" . $pseudo), // user can use reset password to create a password					
					'access_token' => $providerUser->token,
				]);
				$user->save();
				UserAccount::where($authUser->provider . '_id', $authUser->id)
					->update([$driver . '_id' => $user->id]);
				DB::commit();
			} catch (\Exception $e) {
				// something went wrong elsewhere, handle gracefully
				DB::rollBack();
				Session::flash('flash_type', 'alert-danger');
				Session::flash('flash_message', '<p><b>Error!</b> Database error code:'. $e->getMessage() .'</p>');
				return redirect()->route('auth.index');
			}
			Session::flash('flash_type', 'alert-success');
			Session::flash('flash_message', '<p><b>Welcome '.$pseudo.'!</b> Your account with '.ucfirst($driver).' has been successfly connected.</p>');
			return $this->redirectPreviousOrView();
		}

		/////
		///// 3. has been already connected with this provider but not authenticated
		/////
		if (! is_null($userFromProvider) && is_null($authUser)) { 
			// refresh access token
			DB::beginTransaction();
			try {
				$userFromProvider->update([
					'provider' => $driver,
					'provider_id' => $providerUser->id,
					'access_token' => $providerUser->token
				]);
				$userFromProvider->save();
				DB::commit();
			} catch (\Exception $e) {
				// something went wrong elsewhere, handle gracefully
				DB::rollBack();
				Session::flash('flash_type', 'alert-danger');
				Session::flash('flash_message', '<p><b>Error!</b> Database error code:'. $e->getMessage() .'</p>');
				return redirect()->route('auth.index');
			}
			Auth::loginUsingId($userFromProvider->id);
			Session::flash('flash_type', 'alert-success');
			Session::flash('flash_message', '<p><b>Welcome back '.Auth::user()->pseudo().'!</b> You are ready to manage your data.</p>');
			return $this->redirectPreviousOrView();
		}

		/////
		///// 4. has been already connected with this provider and authenticated 
		/////
		if (! is_null($userFromProvider) && !is_null($authUser)) {

			// refresh access token
			DB::beginTransaction();
			try {
				User::where('provider', $driver)
					->where('provider_id', $providerUser->id)
					->update(['access_token' => $providerUser->token]);

				DB::commit();
			} catch (\Exception $e) {
				// something went wrong elsewhere, handle gracefully
				DB::rollBack();
				Session::flash('flash_type', 'alert-danger');
				Session::flash('flash_message', '<p><b>Error!</b> Database error code:'. $e->getMessage() .'</p>');
				return redirect()->route('auth.index');
			}

			Session::flash('flash_type', 'alert-success');
			Session::flash('flash_message', '<p><b>Welcome '.$authUser->pseudo().'!</b> Your session has been refreshed with '.ucfirst($driver).'.</p>');
			return $this->redirectPreviousOrView();
		}
	}

	public function redirectPreviousOrView() {
		if(!Auth::check()) {
			Session::flash('flash_type', 'alert-danger');
			Session::flash('flash_message', '<p><b>Oups !</b> Something went wrong.... You are not authenticated.</p>');
		}

		$redirect = Session::get('__redirect_url');
		if (isset($redirect)) {
			Session::forget('__redirect_url');
			return redirect($redirect);
		} else {
			return redirect()->route('auth.index');
		}
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
