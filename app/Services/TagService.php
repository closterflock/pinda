<?php


namespace App\Services;


use App\Models\Repository\TagRepository;
use App\Models\Tag;
use App\Models\User;
use Laracore\Factory\ModelFactory;

class TagService
{
    /**
     * @var TagRepository
     */
    private $repository;
    /**
     * @var ModelFactory
     */
    private $factory;

    public function __construct(TagRepository $repository, ModelFactory $factory)
    {
        $this->repository = $repository;
        $factory->setRepository($repository);
        $this->factory = $factory;
    }

    /**
     * Creates a tag, or returns the already-existing tag.
     *
     * @param User $user
     * @param $name
     * @return Tag
     */
    public function firstOrCreateTag(User $user, $name)
    {
        $tag = $this
            ->repository
            ->getTagForUserByName($user, $name);

        if (!is_null($tag)) {
            return $tag;
        }

        return $this->factory->make([
            'name' => $name
        ], [
            'user' => $user
        ]);
    }

}