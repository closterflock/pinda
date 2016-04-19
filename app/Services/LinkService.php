<?php


namespace App\Services;


use App\Models\Factory\ModelFactory;
use App\Models\Repository\LinkRepository;
use App\Models\User;

class LinkService
{
    /**
     * @var LinkRepository
     */
    private $repository;
    /**
     * @var ModelFactory
     */
    private $factory;

    public function __construct(LinkRepository $repository, ModelFactory $factory)
    {
        $factory->setRepository($repository);
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * Creates the first
     *
     * @param User $user
     * @param $title
     * @param $description
     * @param $url
     * @return \App\Models\AbstractModel|\App\Models\Link|null
     */
    public function saveLink(User $user, $url, $title = null, $description = null)
    {
        $link = $this->repository->getLinkForUserByUrl($url, $user);

        if (!isset($link)) {
            $link =  $this->factory->make([
                'title' => $title,
                'description' => $description,
                'url' => $url
            ], [
                'user' => $user
            ]);
        } else {
            $link->fill([
                'title' => $title,
                'description' => $description
            ]);
            $link->save();
        }

        return $link;
    }

}