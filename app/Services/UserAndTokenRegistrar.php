<?php


namespace App\Services;


use App\Models\Factory\AuthTokenFactory;
use App\Models\Factory\UserFactory;
use App\Models\User;
use Illuminate\Http\Request;

class UserAndTokenRegistrar
{
    /**
     * @var AuthTokenFactory
     */
    private $authFactory;
    /**
     * @var UserFactory
     */
    private $userFactory;

    public function __construct(AuthTokenFactory $authFactory, UserFactory $userFactory)
    {
        $this->authFactory = $authFactory;
        $this->userFactory = $userFactory;
    }

    /**
     * Creates an auth token from a request.
     *
     * @param Request $request
     * @param User $user
     * @return \App\Models\AuthToken
     */
    public function createAuthTokenFromRequest(Request $request, User $user)
    {
        return $this
            ->authFactory
            ->makeNewToken($user, $request->ip(), $request->headers->get('user-agent'));
    }

    /**
     * Creates a user and an auth token from a request.
     *
     * @param Request $request
     * @return \App\Models\AuthToken
     */
    public function createUserAndAuthTokenFromRequest(Request $request)
    {
        $user = $this
            ->userFactory
            ->makeNewUser($request->name, $request->email, $request->password);

        return $this->createAuthTokenFromRequest($request, $user);
    }

}