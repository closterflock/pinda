<?php
/**
 * Created by PhpStorm.
 * User: jamesspence
 * Date: 4/14/16
 * Time: 10:16 PM
 */

namespace App\Models;


use Illuminate\Foundation\Auth\User;

class Link extends AbstractModel
{

    public $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}