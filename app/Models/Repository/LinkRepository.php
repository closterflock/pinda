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
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Query\Builder;
use Laracore\Repository\ModelRepository;

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
     * Retrieves links for a user by URL.
     *
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

    /**
     * Builds the join query for tags.
     *
     * @param Builder|null $query
     * @return Builder
     */
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
     * Retrieves all links since last sync.
     * When timestamp is null, all records will be retrieved.
     *
     * @param User $user - the user attempting to sync.
     * @param Carbon|null $timestamp - the last timestamp that we are syncing from. Can be null.
     * @return Collection
     */
    public function getLinksSinceLastSync(User $user, Carbon $timestamp = null): Collection
    {
        $query = $this->query()
            ->where('user_id', '=', $user->id);

        if (is_null($timestamp)) {
            return $query->get();
        }

        return $this->query()
            ->withTrashed()
            ->where('updated_at', '>', $timestamp)
            ->orWhere('deleted_at', '>', $timestamp)
            ->get();
    }

    /**
     * Retrieves all associative linkTag records between a set of links and tags.
     *
     * @param Collection $links
     * @param Collection $tags
     * @return SupportCollection
     */
    public function getAllLinkTags(Collection $links, Collection $tags): SupportCollection
    {
        return \DB::table('link_tag')
            ->whereIn('link_id', $links->pluck('id'))
            ->orWhereIn('tag_id', $tags->pluck('id'))
            ->get();
    }

}