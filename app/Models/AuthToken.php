<?php


namespace App\Models;


/**
 * @property User user
 */
class AuthToken extends AbstractModel
{
    public $guarded = ['id'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'user', 'user_id', 'user_agent', 'ip'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}