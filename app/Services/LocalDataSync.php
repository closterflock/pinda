<?php


namespace App\Services;


use App\Models\Link;
use App\Models\Repository\LinkRepository;
use App\Models\Repository\TagRepository;
use App\Models\Tag;
use App\Models\User;

class LocalDataSync
{
    /**
     * @var LinkRepository
     */
    private $linkRepository;
    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(LinkRepository $linkRepository, TagRepository $tagRepository)
    {
        $this->linkRepository = $linkRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * Retrieves all data that needs to be synced up since last timestamp.
     *
     * @param User $user - the user attempting to sync.
     * @param null $timestamp - the timestamp of last sync. Can be null.
     * @return SyncData
     */
    public function getDataToSync(User $user, $timestamp = null): SyncData
    {
        $allLinks = $this->linkRepository->getLinksSinceLastSync($user, $timestamp);
        $allTags = $this->tagRepository->getTagsSinceLastSync($user, $timestamp);
        $linkTags = $this->linkRepository->getAllLinkTags($allLinks, $allTags);

        $syncData = new SyncData();
        $syncData->setLinkTags($linkTags);

        if (is_null($timestamp)) {
            $syncData->setLinks($allLinks);
            $syncData->setTags($allTags);

            return $syncData;
        }

        $links = collect();
        $tags = collect();

        $deletedLinks = collect();
        $deletedTags = collect();

        $allLinks->each(function (Link $link) use ($links, $deletedLinks) {
            if ($link->trashed()) {
                $deletedLinks->push($link);
            } else {
                $links->push($link);
            }
        });

        $allTags->each(function (Tag $tag) use ($tags, $deletedTags) {
            if ($tag->trashed()) {
                $deletedTags->push($tag);
            } else {
                $tags->push($tag);
            }
        });

        $syncData->setLinks($links);
        $syncData->setDeletedLinks($deletedLinks);
        $syncData->setTags($tags);
        $syncData->setDeletedTags($deletedTags);

        return $syncData;
    }

}