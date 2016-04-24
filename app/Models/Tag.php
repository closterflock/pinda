<?php


namespace App\Models;


class Tag extends AbstractModel
{

    public function links()
    {
        return $this->belongsToMany(Link::class);
    }

}