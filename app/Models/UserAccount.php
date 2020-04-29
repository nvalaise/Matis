<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserAccount extends Model
{
    use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
	    'pseudo', 
	    'deezer_id', 'spotify_id', 'discogs_id',
	];

	public function user($provider) {
		switch ($provider) {
            case 'deezer':
                if (! is_null($this->deezer_id)) {
					return $this->hasOne('App\Models\User', 'id', 'deezer_id')->first();
                }
                break;
            
            case 'spotify':
                if (! is_null($this->spotify_id)) {
                    return $this->hasOne('App\Models\User', 'id', 'spotify_id')->first();
                }
                break;
        }
        return null;
    }

    protected $table = 'users_accounts';

    public $timestamps = false;
}
