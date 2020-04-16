<?php

namespace App\Extensions;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class DeezerUserProvider implements UserProvider
{

    /**
     * The Eloquent user model.
     *
     * @var string
     */
    protected $model;

    /**
     * Create a new database user provider.
     *
     * @param  string  $model
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }
    /**
     * @param $identifier
     */
    public function retrieveById($identifier)
    {
        $user = $this->model->find($identifier);

        return $this->getGenericUser($user);
    }
    /**
     * @param $identifier
     * @param $token
     */
    public function retrieveByToken($identifier, $token) {}
    

    /**
     * @param Authenticatable $user
     * @param $token
     */
    public function updateRememberToken(Authenticatable $user, $token) { }

    /**
     * @param array $credentials
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return;
        }

        $user = $this->model->fetchUserByCredentials(['deezerId' => $credentials['deezerId'],'email' => $credentials['email']]);

        return $user;
    }

    /**
     * @param Authenticatable $user
     * @param array $credentials
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return ($credentials['deezerId'] == $user->getAuthIdentifier()) &&
            ($credentials['email'] == $user->getAuthPassword());
    }

    /**
     * Get the generic user.
     *
     * @param  mixed  $user
     * @return \Illuminate\Auth\GenericUser|null
     */
    protected function getGenericUser($user)
    {
        if (! is_null($user)) {
            return new GenericUser((array) $user);
        }
    }
}