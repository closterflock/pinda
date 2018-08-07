<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tag;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy extends AbstractPolicy
{

    /**
     * Determine whether the user can view the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function view(User $user, Tag $tag)
    {
        return $this->owns($user, $tag);
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function update(User $user, Tag $tag)
    {
        return $this->owns($user, $tag);
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function delete(User $user, Tag $tag)
    {
        return $this->owns($user, $tag);
    }



    /**
     * Determines if a user owns a model.
     *
     * @param User $user
     * @param Tag $tag
     * @return bool
     */
    private function owns(User $user, Tag $tag)
    {
        return $user->id === $tag->user_id;
    }
}
