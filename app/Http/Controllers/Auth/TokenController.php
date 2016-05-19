<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\API\APIController;
use App\Http\Middleware\GenerateTokenAuth;
use App\Http\Response\APIResponseFactory;
use App\Services\UserAndTokenRegistrar;
use Illuminate\Auth\AuthManager;
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
     * @param UserAndTokenRegistrar $registrar
     * @return \App\Models\AbstractModel
     */
    public function newToken(Request $request, UserAndTokenRegistrar $registrar)
    {
        $authToken = $registrar->createAuthTokenFromRequest($request, $request->user());

        return $this->successResponse('Success', [
            'token' => $authToken->token
        ]);
    }

}