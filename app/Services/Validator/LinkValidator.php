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

    public function linkAlreadyExists(LinkRepository $repository, User $user, $url)
    {
        $link = $repository->getLinkForUserByUrl($user, $url);
        return isset($link);
    }
}