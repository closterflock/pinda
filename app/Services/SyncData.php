<?php


namespace App\Services;



use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

class SyncData implements Arrayable, Jsonable, \JsonSerializable
{
    /**
     * @var Collection
     */
    private $links;
    /**
     * @var Collection
     */
    private $tags;
    /**
     * @var Collection
     */
    private $linkTags;
    /**
     * @var Collection
     */
    private $deletedLinks;
    /**
     * @var Collection
     */
    private $deletedTags;
    /**
     * @var Carbon
     */
    private $timestamp;

    public function __construct()
    {
        $this->updateTimestamp();
        $this->links = collect();
        $this->tags = collect();
        $this->linkTags = collect();
        $this->deletedLinks = collect();
        $this->deletedTags = collect();
    }

    /**
     * @param Collection $links
     * @return SyncData
     */
    public function setLinks(Collection $links)
    {
        $this->links = $links;
        return $this;
    }

    /**
     * @param Collection $tags
     * @return SyncData
     */
    public function setTags(Collection $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @param Collection $linkTags
     * @return SyncData
     */
    public function setLinkTags(Collection $linkTags)
    {
        $this->linkTags = $linkTags;
        return $this;
    }

    /**
     * @param Collection $deletedLinks
     * @return SyncData
     */
    public function setDeletedLinks(Collection $deletedLinks)
    {
        $this->deletedLinks = $deletedLinks;
        return $this;
    }

    /**
     * @param Collection $deletedTags
     * @return SyncData
     */
    public function setDeletedTags(Collection $deletedTags)
    {
        $this->deletedTags = $deletedTags;
        return $this;
    }

    public function updateTimestamp() {
        $this->timestamp = Carbon::now()->subMinute();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'links' => $this->links,
            'tags' => $this->tags,
            'link_tags' => $this->linkTags,
            'deleted_links' => $this->deletedLinks,
            'deleted_tags' => $this->deletedTags,
            'timestamp' => $this->timestamp->toIso8601ZuluString()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}