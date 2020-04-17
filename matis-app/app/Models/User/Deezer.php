<?php

namespace App\Models\User;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        if ($this->deezerId === $credentials['deezerId'] && $this->email === $credentials['email']) {
            $arr_user = $this->email;
            return $this;
        }

        return null;
    }
}
