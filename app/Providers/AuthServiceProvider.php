<?php

namespace App\Providers;

use App\Models\AuthToken;
use App\Models\Link;
use App\Models\Tag;
use App\Policies\LinkPolicy;
use App\Policies\TagPolicy;
use Illuminate\Http\Request;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laracore\Repository\ModelRepository;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Link::class => LinkPolicy::class,
        Tag::class => TagPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerApiGuard();
    }

    private function registerApiGuard()
    {
        /** @var ModelRepository $repository */
        $repository = $this->app->make(ModelRepository::class);
        $repository->setModel(AuthToken::class);

        \Auth::viaRequest('api-token', function (Request $request) use ($repository) {
            $apiToken = $request->headers->get('X-Auth-Token');

            /** @var AuthToken $authToken */
            $authToken = $repository
                ->with('user')
                ->select('auth_tokens.*')
                ->join('users', 'auth_tokens.user_id', '=', 'users.id')
                ->where('token', '=', $apiToken)
                ->first();

            if (is_null($authToken)) {
                return null;
            }

            $user = $authToken->user;
            $user->setAuthToken($authToken);

            return $user;
        });
    }
}
