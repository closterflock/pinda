<?php


namespace App\Models\Repository;


use App\Models\AuthToken;
use App\Models\Factory\AuthTokenFactory;
use App\Models\User;
use Laracore\Repository\ModelRepository;
use Laracore\Repository\Relation\RelationInterface;

class AuthTokenRepository extends ModelRepository
{

    public function __construct($model = null, RelationInterface $repository = null)
    {
        if (is_null($model)) {
            $model = AuthToken::class;
        }

        parent::__construct($model, $repository);
    }

    /**
     * Fetches or creates a new auth token for a user based on IP and User Agent.
     *
     * @param User $user
     * @param $ip
     * @param $userAgent
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreateForUser(AuthTokenFactory $factory, User $user, $ip, $userAgent)
    {
        $authToken = $this->query()
            ->where('user_id', '=', $user->id)
            ->where('ip', '=', $ip)
            ->where('user_agent', '=', $userAgent)
            ->first();

        if (isset($authToken)) {
            return $authToken;
        } else {
            return $factory->makeNewToken($user, $ip, $userAgent);
        }
    }

}