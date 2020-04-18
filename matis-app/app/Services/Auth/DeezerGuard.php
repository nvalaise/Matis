<?php

namespace App\Services\Auth;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class DeezerGuard extends SessionGuard
{

    /**
     * The name of the query string item from the request containing the API token.
     *
     * @var string
     */
    protected $inputKey;

    /**
     * The name of the token "column" in persistent storage.
     *
     * @var string
     */
    protected $storageKey;

	/**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     */
    public function __construct(UserProvider $provider,
                                Request $request,
                                Session $session,

                                $name = 'deezer',
                                $inputKey = 'accesstokenDeezer',
                                $storageKey = 'accessToken')
    {
        parent::__construct($name, $provider, $session, $request);

        $this->inputKey = $inputKey;
        $this->storageKey = $storageKey;
    }


    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    public function login(AuthenticatableContract $user, $remember = false)
    {
        parent::login($user, $remember);

        Auth::loginUsingId($user->id);
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user() {

        $user = $this->deezer();
        if (! is_null($user)) {
            return $user->user();
        }

        return null;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function deezer() {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->token();

        if (! empty($token)) {
            $user = $this->provider->retrieveByCredentials([
                $this->storageKey => $token,
            ]);
        }

        return $this->user = $user;
    }

    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function token()
    {
        $token = $this->request->query($this->inputKey);

        if (empty($token)) {
            $token = $this->request->input($this->inputKey);
        }

        if (empty($token)) {
            $token = $this->session->get($this->inputKey);
        }

        return $token;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        if (!is_null($this->token())) {
            return true;
        }

        if (!is_null($this->user())) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return !$this->check();
    }

        /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        parent::logout();
        
        $this->session->forget($this->inputKey);
        Auth::logout();
    }
}