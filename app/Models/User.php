<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id', 'provider', 
        'email', 'password', 
        'access_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function accounts() {
        return $this->hasOne('App\Models\UserAccount', $this->provider.'_id', 'id')->first();
    }

    public function pseudo() {
        $accounts = $this->accounts();

        if (!is_null($accounts)) {
            return $accounts->pseudo;
        }
        return null;
    }

    public function has($driver) {
        $accounts = $this->accounts();
        
        return ! empty((array) $accounts[$driver.'_id']);
    }

    /**
     * Get the phone record associated with the user.
     */
    public function deezer()
    {
        return $this->hasOne('App\Models\UserAccount', 'deezer_id', 'id');
    }

    /**
     * Get the phone record associated with the user.
     */
    public function spotify()
    {
        return $this->hasOne('App\Models\UserAccount', 'spotify_id', 'id');
    }

    /**
     * Get the user pseudonyme.
     *
     * @param  string  $value
     * @return string
     */
    public function getProviderIdAttribute($value)
    {
        return $value;
    }

    /**
     * Get the user pseudonyme.
     *
     * @param  string  $value
     * @return string
     */
    public function getProviderAttribute($value)
    {
        return $value;
    }


    /**
     * Get the user email address.
     *
     * @param  string  $value
     * @return string
     */
    public function getEmailAttribute($value)
    {
        return $value;
    }

    /**
     * Get the user email address.
     *
     * @param  string  $value
     * @return string
     */
    public function getAccessTokenAttribute($value)
    {
        return $value;
    }
}
