<?php


namespace App\Http\Middleware;


use App\Http\Response\APIResponseFactory;
use Closure;
use Illuminate\Auth\AuthManager;

class GenerateTokenAuth
{
    /**
     * @var AuthManager
     */
    private $auth;
    /**
     * @var APIResponseFactory
     */
    private $factory;

    public function __construct(AuthManager $auth, APIResponseFactory $factory)
    {
        $this->auth = $auth;
        $this->factory = $factory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!$this->auth->guard()->validate($credentials)) {
            return $this->factory->make('invalid_auth', 'Authentication parameters are invalid.', [], 401);
        }

        return $next($request);
    }

}