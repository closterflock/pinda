<?php


namespace App\Models;


/**
 * @property User user
 */
class AuthToken extends AbstractModel
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}