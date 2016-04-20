<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\API\APIController;
use App\Http\Middleware\GenerateTokenAuth;
use App\Http\Response\APIResponseFactory;
use App\Models\Factory\AuthTokenFactory;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\RequestGuard;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class TokenController extends APIController
{
    public function __construct(APIResponseFactory $responseFactory, AuthManager $auth)
    {
        $this->middleware(GenerateTokenAuth::class);

        parent::__construct($responseFactory);
    }

    /**
     * Generates a new access token, and returns it.
     *
     * @param Request $request
     * @param AuthTokenFactory $factory
     * @return \App\Models\AbstractModel
     */
    public function newToken(Request $request, AuthTokenFactory $factory)
    {
        $authToken = $factory->makeNewToken($request->user(), $request->ip(), $request->headers->get('user-agent'));

        return $this->successResponse('Success', [
            'token' => $authToken->token
        ]);
    }

}