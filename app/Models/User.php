<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property integer id
 * @property string name
 * @property string email
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * @var AuthToken the current AuthToken being used by the user (if logged in via API)
     */
    private $currentAuth;

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

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function getAuthToken()
    {
        return $this->currentAuth;
    }

    public function setAuthToken(AuthToken $token)
    {
        $this->currentAuth = $token;
    }
}
