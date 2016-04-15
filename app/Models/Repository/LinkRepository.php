<?php
/**
 * Created by PhpStorm.
 * User: jamesspence
 * Date: 4/14/16
 * Time: 10:21 PM
 */

namespace App\Models\Repository;


use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class LinkRepository extends ModelRepository
{

    public function __construct()
    {
        parent::__construct(Link::class);
    }

    /**
     * Retrieves a collection of links for a user.
     *
     * @param User $user
     * @return Collection
     */
    public function getLinksForUser(User $user)
    {
        return $this
            ->query()
            ->where('user_id', '=', $user->id)
            ->get();
    }

}