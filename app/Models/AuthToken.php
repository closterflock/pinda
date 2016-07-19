<?php


namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property User user
 */
class AuthToken extends AbstractModel
{
    use SoftDeletes;

    public $guarded = ['id'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'user', 'user_id', 'user_agent', 'ip'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}