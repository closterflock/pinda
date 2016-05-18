<?php


namespace App\Http\Controllers\API;


use App\Models\User;
use App\Services\UserAndTokenRegistrar;
use Illuminate\Http\Request;
use Laracore\Repository\ModelRepository;

class RegistrationController extends APIController
{


    /**
     * Registers a user via the API.
     *
     * @param Request $request
     * @param ModelRepository $repository
     * @param UserAndTokenRegistrar $registrar
     * @return \App\Models\AuthToken|\Illuminate\Http\Response
     */
    public function registerUser(Request $request, ModelRepository $repository, UserAndTokenRegistrar $registrar)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $repository->setModel(User::class);
        $user = $repository->query()->where('email', '=', $request->email)->first();
        if (isset($user)) {
            return $this->resourceExistsError('User already exists for this email.');
        }

        return $registrar->createUserAndAuthTokenFromRequest($request);
    }

}