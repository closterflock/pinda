<?php


namespace App\Services;


use App\Models\Tag;
use App\Models\User;
use Laracore\Factory\ModelFactory;
use Laracore\Repository\ModelRepository;

class TagService
{
    /**
     * @var ModelRepository
     */
    private $repository;
    /**
     * @var ModelFactory
     */
    private $factory;

    public function __construct(ModelRepository $repository, ModelFactory $factory)
    {
        $repository->setModel(Tag::class);
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
        /** @var Tag $tag */
        $tag = $this
            ->repository
            ->query()
            ->where('name', '=', $name)
            ->where('user_id', '=', $user->id)
            ->first();

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