<?php


namespace App\Http\Controllers\API;


use App\Models\Factory\AuthTokenFactory;
use App\Models\Repository\AuthTokenRepository;
use App\Models\User;
use App\Services\UserAndTokenRegistrar;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTGuard;

class CredentialController extends APIController
{

    /**
     * Registers a user via the API.
     *
     * @route /api/v1/register
     * @method POST
     * @param Request $request
     * @param UserAndTokenRegistrar $registrar
     * @return \App\Models\AuthToken|\Illuminate\Http\Response
     */
    public function registerUser(Request $request, UserAndTokenRegistrar $registrar)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        return $this->successResponse('Success', $registrar->createUserAndAuthTokenFromRequest($request));
    }

    /**
     * Logs in a user via the API.
     *
     * @route /api/v1/login
     * @method POST
     * @param Request $request
     * @param AuthManager $auth
     * @return \App\Models\AuthToken|\Illuminate\Http\Response
     */
    public function login(Request $request, AuthManager $auth)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        /** @var JWTGuard $guard */
        $guard = $auth->guard('api');

        if (!$token = $guard->attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->errorResponse('Error', ['Email or password is incorrect.'], 401);
        }

        return $this->successResponse(
            'Success',
            [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $guard->factory()->getTTL() * 60
            ]
        );
    }

    public function logout(AuthManager $auth)
    {
        $auth->guard('api')->logout();

        return $this->successResponse();
    }

}