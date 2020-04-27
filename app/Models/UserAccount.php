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

    protected $table = 'users_accounts';

    public $timestamps = false;
}
