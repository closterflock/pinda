<?php


namespace Tests\Feature\API\Auth;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Claims\Collection;
use Tymon\JWTAuth\Claims\Expiration;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Factory;
use Tymon\JWTAuth\Payload;

class RefreshTokenTest extends TestCase
{
    use RefreshDatabase;

    public function testRefreshNoToken()
    {
        $this->stub();
    }

    public function testRefreshExpiredToken()
    {
        \JWTAuth::refresh();

        /** @var Factory $factory */
        $factory = \JWTAuth::factory();

        /** @var JWTSubject $subject */
        $subject = factory(User::class)->create();

        $payload = \JWTAuth::makePayload($subject);
        $payloadArr = $payload->toArray();
        $payloadArr['exp'] = Carbon::now()->subDays(7)->timestamp;
        $expiredPayload = $factory->setRefreshFlow(true)
            ->claims($payloadArr)
            ->make();

        \JWTAuth::setToken(\JWTAuth::encode($expiredPayload));
        \JWTAuth::refresh();

//        exit(Carbon::createFromTimestamp($expiredPayload->get('exp'))->toDateTimeString());
        exit($expiredPayload->toJson());

        $claimCollection = new Collection();
        $expiresClaim = new Expiration(Carbon::now()->subDays(7));
        $claimCollection->put($expiresClaim->getName(), $expiresClaim);

        \JWTAuth::fromUser();
//        \JWTAuth::factory()->withClaims()->setRefreshFlow()->make(true);
        $claims = ['exp' => Carbon::now()->subDays(7)->timestamp];
        /** @var Payload $payload */
        $payload = \JWTAuth::claims($claims)->makePayload($subject);
        $payload->encode();
//        exit(new Carbon($payload->get('exp')));
        exit(Carbon::createFromTimestamp($payload->get('exp'))->toDateTimeString());
        $this->stub();
    }

    public function testRefreshSuccess()
    {
        $this->stub();
    }

}