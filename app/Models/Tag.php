<?php


namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends AbstractModel
{
    use SoftDeletes;

    public $fillable = ['name'];

    public $timestamps = false;

    public function links()
    {
        return $this->belongsToMany(Link::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}