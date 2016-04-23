<?php


namespace App\Services\Validator;


use App\Models\Link;
use App\Models\Repository\LinkRepository;
use App\Models\User;

class LinkValidator extends ValidatorService
{

    /**
     * Retrieves the default rules.
     *
     * @return array
     */
    public function getDefaultRules()
    {
        return [
            'url' => ['required', 'url']
        ];
    }

    /**
     * Checks if a link belongs to a user.
     *
     * @param User $user
     * @param Link $link
     * @return bool
     */
    public function linkBelongsToUser(User $user, Link $link)
    {
        return ($link->user_id === $user->id);
    }

    public function linkAlreadyExists(LinkRepository $repository, User $user, $url)
    {
        $link = $repository->getLinkForUserByUrl($user, $url);
        return isset($link);
    }
}