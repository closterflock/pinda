<?php


namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property int user_id
 * @property string name
 */
class Tag extends AbstractModel
{
    use SoftDeletes;

    public $fillable = ['name'];

    public $timestamps = false;

    protected $casts = [
        'user_id' => 'int'
    ];

    public function links()
    {
        return $this->belongsToMany(Link::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}