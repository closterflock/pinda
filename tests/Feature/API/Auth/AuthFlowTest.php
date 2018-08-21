<?php


namespace Tests\Feature\API\Auth;


use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    const SUCCESS_EMAIL = 'success@pinda.test';
    const SUCCESS_PASSWORD = 'secret';

    /**
     * @var User
     */
    private $user;
    /**
     * @var Link
     */
    private $link;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $user = factory(User::class)
            ->create([
                'email' => static::SUCCESS_EMAIL,
                'password' => \Hash::make(static::SUCCESS_PASSWORD)
            ]);

        /** @var Link $link */
        $link = factory(Link::class)
            ->make();
        $link->user()->associate($user);
        $link->save();

        $this->user = $user;
        $this->link = $link;
    }

    public function testAuthFlowNoToken()
    {
        $response = $this->getLinks();

        $response->assertStatus(401);
    }

    public function testAuthFlowWithToken()
    {
        $token = $this->getToken($this->login());

        $linkResponse = $this->getLinks($token);
        $linkResponse->assertSuccessful();
    }

    public function testAuthFlowLogout()
    {
        $token = $this->getToken($this->login());

        $response = $this->logout($token);

        $response->assertSuccessful();

        $linkResponse = $this->getLinks($token);
        $linkResponse->assertStatus(401);
    }

    private function getLinks(string $token = null): TestResponse
    {
        $headers = !is_null($token) ? ['Authorization' => 'Bearer ' . $token] : [];

        return $this->withHeaders($headers)
            ->getJson(route('api.links.getLinks'));
    }

    private function login(): TestResponse
    {
        return $this->postJson(route('api.login'), [
            'email' => static::SUCCESS_EMAIL,
            'password' => static::SUCCESS_PASSWORD
        ]);
    }

    private function logout(string $token = null): TestResponse
    {

        $headers = !is_null($token) ? ['Authorization' => 'Bearer ' . $token] : [];

        return $this->withHeaders($headers)
            ->deleteJson(route('api.logout'));
    }

    private function getToken(TestResponse $response)
    {
        $response->assertSuccessful();

        $data = $response->json('data');
        $this->assertNotNull($data);
        $this->assertArrayHasKey('token', $data);

        $token = $data['token'];

        return $token;
    }

}