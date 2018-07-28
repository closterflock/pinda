<?php


namespace Tests\Feature\API;


use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class LinkCrudTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;
    use MakesAuthenticatedRequest;

    /**
     * @var User
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->user = $this->createUser();
    }

    public function testGetLinks()
    {
        /** @var Collection $links */
        $links = $this->createLinks($this->user);
        $linkIds = $links->pluck('id');

        $otherUser = $this->createUser();
        $this->createLinks($otherUser);

        $response = $this->makeRequest(route('api.links.getLinks'), 'GET', [], $this->user);

        $response->assertSuccessful();
        $data = $response->json('data');

        $this->assertNotNull($data);

        $this->assertArrayHasKey('links', $data);

        $responseLinks = $data['links'];
        $this->assertCount(3, $responseLinks);
        foreach ($responseLinks as $responseLink) {
            $this->assertEquals($this->user->id, $responseLink['user_id']);
            $this->assertContains($responseLink['id'], $linkIds);
        }
    }

    public function testNewLinkValidationFailure()
    {

    }

    public function testNewLinkSuccess()
    {
        $this->stub();
    }

    public function testGetLinkNotFound()
    {
        $this->stub();
    }

    public function testGetLinkNotOwnedByUser()
    {
        $this->stub();
    }

    public function testGetLinkSuccess()
    {
        $this->stub();
    }

    public function testDeleteLinkNotFound()
    {
        $this->stub();
    }

    public function testDeleteLinkNotOwnedByUser()
    {
        $this->stub();
    }

    public function testDeleteLinkSuccess()
    {
        $this->stub();
    }

    public function testUpdateLinkNotOwnedByUser()
    {
        $this->stub();
    }

    public function testUpdateLinkSuccess()
    {
        $this->stub();
    }

    private function createLinks(User $user, $number = 3) {
        return factory(Link::class, $number)
            ->make()
            ->each(function (Link $link) use ($user) {
                $link->user()->associate($user);
                $link->save();
            });
    }
}