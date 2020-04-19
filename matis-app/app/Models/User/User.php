<?php

namespace App\Models\User;

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
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the central user account.
     */
    public function deezer()
    {
        return $this->hasOne('App\Models\User\Deezer', 'id', 'id');
    }


    /**
     * Get the user pseudonyme.
     *
     * @param  string  $value
     * @return string
     */
    public function getNamedAttribute($value)
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
}
