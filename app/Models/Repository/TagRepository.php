<?php


namespace App\Models\Repository;


use App\Models\Tag;
use App\Models\User;
use Laracore\Repository\ModelRepository;
use Laracore\Repository\Relation\RelationInterface;

class TagRepository extends ModelRepository
{

    public function __construct($model = null, RelationInterface $repository = null)
    {
        if (is_null($model)) {
            $model = Tag::class;
        }

        parent::__construct($model, $repository);
    }

    /**
     * Gets a tag for a user based on name.
     *
     * @param User $user
     * @param $name
     * @return Tag|null
     */
    public function getTagForUserByName(User $user, $name)
    {
        return $this->query()
            ->where('name', '=', $name)
            ->where('user_id', '=', $user->id)
            ->first();
    }

    /**
     * Retrieves all tags for a user.
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTagsForUser(User $user)
    {
        return $this->query()
            ->where('user_id', '=', $user->id)
            ->get();
    }

}