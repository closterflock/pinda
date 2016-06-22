<?php


namespace App\Http\Controllers\API;


use App\Models\Factory\AuthTokenFactory;
use App\Services\UserAndTokenRegistrar;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class RegistrationController extends APIController
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

    public function login(Request $request, AuthManager $auth, AuthTokenFactory $factory)
    {
        if (!$auth->once(['email' => $request->email, 'password' => $request->password])) {
            return $this->errorResponse('Error', ['Email or password is incorrect.']);
        }

        return $factory->makeNewToken($request->user(), $request->ip(), $request->header('User-Agent'));
    }

}