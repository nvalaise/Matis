<?php

namespace App\Models\User;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Model\Auth\User;


class Deezer extends Authenticatable
{
    use Notifiable;

    protected $guard = 'deezer';

    protected $table = "users_deezer";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deezerId', 'name', 'email', 'firstname', 'lastname', 'status', 'inscriptionDate', 'profileLink', 'picture', 'country', 'lang', 'isKid', 'tracklist'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ ];

    /**
     * Fetch user by Credentials
     *
     * @param array $credentials
     * @return Illuminate\Contracts\Auth\Authenticatable
     */
    public function fetchUserByCredentials(array $credentials)
    {

        if (array_key_exists("deezerId", $credentials) && array_key_exists("email", $credentials)) {
            return 
                DB::table('users_deezer')
                    ->where('deezerId', '=', $credentials['deezerId'])
                    ->where('email', '=', $credentials['email'])
                    ->first();
        }

        return null;
    }

    /**
     * Get the central user account.
     */
    public function user()
    {
        return $this->hasOne('App\Models\User\User', 'id', 'id')->first();
    }

    /**
     * Get the Deezer identifier.
     *
     * @param  string  $value
     * @return string
     */
    public function getDeezerIdAttribute($value)
    {
        return $value;
    }

    /**
     * Get the Deezer email.
     *
     * @param  string  $value
     * @return string
     */
    public function getEmailAttribute($value)
    {
        return $value;
    }

    /**
     * Get the Deezer email.
     *
     * @param  string  $value
     * @return string
     */
    public function getAccessTokenAttribute($value)
    {
        return $value;
    }
}
