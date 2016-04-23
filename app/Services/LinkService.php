<?php


namespace App\Services;


use App\Models\Factory\ModelFactory;
use App\Models\Link;
use App\Models\Repository\LinkRepository;
use App\Models\User;

class LinkService
{
    private $repository;

    public function __construct(LinkRepository $repository)
    {
        $this->repository = $repository;
    }

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

    public function newLink(ModelFactory $factory, User $user, $url, $title = null, $description = null)
    {
        $factory->setRepository($this->repository);

        $factory->make([
            'url' => $url,
            'title' => $title,
            'description' => $description
        ], [
            'user' => $user
        ]);
    }

}