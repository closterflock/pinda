<?php


namespace App\Http\Controllers\API;


use App\Services\UserAndTokenRegistrar;
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

}