<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Link;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkPolicy extends AbstractPolicy
{

    /**
     * Determine whether the user can view the link.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Link  $link
     * @return boolean
     */
    public function view(User $user, Link $link)
    {
        return $this->owns($user, $link);
    }

    /**
     * Determine whether the user can update the link.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Link  $link
     * @return boolean
     */
    public function update(User $user, Link $link)
    {
        return $this->owns($user, $link);
    }

    /**
     * Determine whether the user can delete the link.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Link  $link
     * @return boolean
     */
    public function delete(User $user, Link $link)
    {
        return $this->owns($user, $link);
    }

    /**
     * Determines if a user owns a model.
     *
     * @param User $user
     * @param Link $link
     * @return bool
     */
    private function owns(User $user, Link $link)
    {
        return $user->id === $link->user_id;
    }
}
