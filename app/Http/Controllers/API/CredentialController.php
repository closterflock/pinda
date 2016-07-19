<?php


namespace App\Http\Controllers\API;


use App\Models\Factory\AuthTokenFactory;
use App\Models\Repository\AuthTokenRepository;
use App\Models\User;
use App\Services\UserAndTokenRegistrar;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

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
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

        return $registrar->createUserAndAuthTokenFromRequest($request);
    }

    /**
     * Logs in a user via the API.
     *
     * @route /api/v1/login
     * @method POST
     * @param Request $request
     * @param AuthManager $auth
     * @param AuthTokenRepository $repository
     * @return \App\Models\AuthToken|\Illuminate\Http\Response
     */
    public function login(Request $request, AuthManager $auth, AuthTokenRepository $repository)
    {
        if (!$auth->once(['email' => $request->email, 'password' => $request->password])) {
            return $this->errorResponse('Error', ['Email or password is incorrect.']);
        }

        return $this->successResponse(
            'Success',
            $repository->firstOrCreateForUser(
                new AuthTokenFactory(),
                $request->user(),
                $request->ip(),
                $request->header('User-Agent')
            )
        );
    }

    public function logout(Request $request, AuthTokenRepository $repository)
    {
        /** @var User $user */
        $user = $request->user();

        $repository->deleteAuthToken($user->getAuthToken());
    }

}