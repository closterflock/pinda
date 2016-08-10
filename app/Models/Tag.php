<?php


namespace App\Models;


class Tag extends AbstractModel
{
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