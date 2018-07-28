<?php
/**
 * Created by PhpStorm.
 * User: jamesspence
 * Date: 4/14/16
 * Time: 10:16 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed id
 * @property mixed user_id
 * @property User user
 * @property string title
 * @property string description
 * @property string url
 */
class Link extends AbstractModel
{
    use SoftDeletes;

    protected $casts = [
        'user_id' => 'int'
    ];

    public $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

}