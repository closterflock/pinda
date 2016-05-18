<?php


namespace App\Services;


use App\Models\Link;
use App\Models\Repository\LinkRepository;
use App\Models\User;
use Laracore\Factory\ModelFactory;

class LinkService
{
    private $repository;

    public function __construct(LinkRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Saves a link.
     *
     * @param Link $link
     * @param $url
     * @param null $title
     * @param null $description
     * @return Link
     */
    public function saveLink(Link $link, $url, $title = null, $description = null)
    {
        $link->fill([
            'url' => $url,
            'title' => $title,
            'description' => $description
        ]);

        $link->save();

        return $link;
    }

    /**
     * Creates a new link.
     *
     * @param ModelFactory $factory
     * @param User $user
     * @param $url
     * @param null $title
     * @param null $description
     * @return Link
     */
    public function newLink(ModelFactory $factory, User $user, $url, $title = null, $description = null)
    {
        $factory->setRepository($this->repository);

        return $factory->make([
            'url' => $url,
            'title' => $title,
            'description' => $description
        ], [
            'user' => $user
        ]);
    }

}