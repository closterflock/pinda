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
use Illuminate\Database\Query\Builder;

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

    /**
     * Retrieves a link for a user.
     *
     * @param $id
     * @param User $user
     * @return Link|null
     */
    public function getLinkForUser($id, User $user)
    {
        return $this
            ->query()
            ->where('user_id', '=', $user->id)
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * @param User $user
     * @param $url
     * @return Link|null
     */
    public function getLinkForUserByUrl(User $user, $url)
    {
        return $this
            ->query()
            ->where('user_id', '=', $user->id)
            ->where('url', '=',$url)
            ->first();
    }

    public function buildTagJoins(Builder $query = null)
    {
        if (is_null($query)) {
            $query = $this->query();
        }

        return $query
            ->select('links.*')
            ->leftJoin('link_tag', 'links.id', '=', 'link_tag.link_id')
            ->leftJoin('tags', 'link_tag.tag_id', '=', 'tags.id');
    }

    /**
     * @param $term
     * @return mixed
     */
    public function getLinksForSearch($term)
    {
        $wildcardTerm = '%' . $term . '%';
        $links = $this
            ->buildTagJoins()
            ->with('tags')
            ->where('links.title', 'LIKE', $wildcardTerm)
            ->orWhere('links.description', 'LIKE', $wildcardTerm)
            ->orWhere('tags.name', 'LIKE', $wildcardTerm)
            ->get();

        return $links;
    }

}