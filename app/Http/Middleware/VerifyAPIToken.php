<?php


namespace App\Http\Middleware;


use App\Http\Response\APIResponseFactory;
use App\Models\AuthToken;
use Closure;
use Illuminate\Auth\AuthManager;
use Laracore\Repository\ModelRepository;

class VerifyAPIToken
{
    /**
     * @var ModelRepository
     */
    private $repository;
    /**
     * @var APIResponseFactory
     */
    private $factory;
    /**
     * @var AuthManager
     */
    private $auth;

    public function __construct(ModelRepository $repository, APIResponseFactory $factory, AuthManager $auth)
    {
        $repository->setModel(AuthToken::class);
        $this->repository = $repository;
        $this->factory = $factory;
        $this->auth = $auth;
    }

    /**
     * Creates an unauthorized response.
     *
     * @param string $status
     * @param string $message
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function createUnauthorizedResponse($status = 'invalid_auth', $message = 'Invalid auth token')
    {
        return $this->factory->make($status, $message, [], 401);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @internal param null|string $guard
     */
    public function handle($request, Closure $next)
    {
        $apiToken = $request->headers->get('X-Auth-Token');

        if (is_null($apiToken)) {
            return $this->createUnauthorizedResponse('missing_auth', 'Missing auth token.');
        }

        /** @var AuthToken $authToken */
        $authToken = $this
            ->repository
            ->with('user')
            ->where('token', '=', $apiToken)
            ->where('ip', '=', $request->ip())
            ->first();

        if (!isset($authToken)) {
            return $this->createUnauthorizedResponse();
        }

        $user = $authToken->user;
        $user->setAuthToken($authToken);

        $this->auth->guard()->setUser($user);

        return $next($request);
    }

}