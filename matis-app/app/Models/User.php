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

    public function has($driver) {
        return $this->hasOne('App\Models\UserAccount', $driver.'_id', 'id')->first();
    }

    /**
     * Get the phone record associated with the user.
     */
    public function deezer()
    {
        return $this->hasOne('App\Models\UserAccount', 'deezer_id', 'id');
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
