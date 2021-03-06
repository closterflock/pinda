<?php


namespace Tests\Feature\API;


use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkCrudTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
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

    public function testGetLinkNotFound()
    {
        /** @var Link $link */
        $link = $this->createLink($this->user);

        //Iterate by one to get an id that doesn't exist.
        $id = $link->id + 1;

        $response = $this->makeRequest(
            $this->generateRouteForLink($id, 'getLink'),
            'GET',
            [],
            $this->user
        );

        $response->assertNotFound();
    }

    public function testGetLinkNotOwnedByUser()
    {
        $otherUser = $this->createUser();

        $link = $this->createLink($otherUser);

        $url = $this->generateRouteForLink($link->id, 'getLink');

        $response = $this->makeRequest($url, 'GET', [], $this->user);

        $response->assertForbidden();
    }

    public function testGetLinkSuccess()
    {
        $link = $this->createLink($this->user);

        $url = $this->generateRouteForLink($link->id, 'getLink');

        $response = $this->makeRequest($url, 'GET', [], $this->user);

        $response->assertSuccessful();

        $data = $response->json('data');

        $this->assertNotNull($data);

        $this->assertArrayHasKey('link', $data);

        $responseLink = $data['link'];

        $this->assertEquals($link->id, $responseLink['id']);
    }

    public function testDeleteLinkNotFound()
    {
        $link = $this->createLink($this->user);

        $id = $link->id + 1;

        $url = $this->generateRouteForLink($id, 'delete');

        $response = $this->makeRequest($url, 'DELETE', [], $this->user);

        $response->assertNotFound();
    }

    public function testDeleteLinkNotOwnedByUser()
    {
        $otherUser = $this->createUser();
        $link = $this->createLink($otherUser);

        $url = $this->generateRouteForLink($link->id, 'delete');

        $response = $this->makeRequest($url, 'DELETE', [], $this->user);

        $response->assertForbidden();
    }

    public function testDeleteLinkSuccess()
    {
        $link = $this->createLink($this->user);

        $url = $this->generateRouteForLink($link->id, 'delete');

        $response = $this->makeRequest($url, 'DELETE', [], $this->user);

        $response->assertSuccessful();

        /** @var Link $link */
        $link = Link::withTrashed()->find($link->id);
        $this->assertTrue($link->trashed());
    }

    public function testUpdateLinkNotFound()
    {
        $url = $this->generateRouteForLink(1, 'update');

        $response = $this->makeRequest($url, 'PUT', [], $this->user);

        $response->assertNotFound();
    }

    public function testUpdateLinkNotOwnedByUser()
    {
        $otherUser = $this->createUser();

        $link = $this->createLink($otherUser);

        $url = $this->generateRouteForLink($link->id, 'update');

        $response = $this->makeRequest($url, 'PUT', [], $this->user);

        $response->assertForbidden();
    }

    public function testUpdateLinkSuccess()
    {
        $link = $this->createLink($this->user);

        $params = [
            'url' => 'http://updated.pinda.test',
            'title' => 'New description',
            'description' => 'New description'
        ];

        $url = $this->generateRouteForLink($link->id, 'update');

        $response = $this->makeRequest($url, 'PUT', $params, $this->user);

        $response->assertSuccessful();

        /** @var Link $refreshedLink */
        $refreshedLink = Link::findOrFail($link->id);

        $this->assertEquals($refreshedLink->url, $params['url']);
        $this->assertEquals($refreshedLink->title, $params['title']);
        $this->assertEquals($refreshedLink->description, $params['description']);
    }

    public function testUpdateLinkNewUrlAlreadyExists()
    {
        $params = [
            'url' => 'http://pinda.test'
        ];
        $this->createLink($this->user, $params);

        $link = $this->createLink($this->user);
        $url = $this->generateRouteForLink($link->id, 'update');

        $response = $this->makeRequest($url, 'PUT', $params, $this->user);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'url'
        ]);

        $secondTryResponse = $this->makeRequest($url, 'PUT', [
            'url' => 'http://brandNewUrl.pinda.test'
        ], $this->user);

        $secondTryResponse->assertSuccessful();
    }

    public function testUpdateLinkUrlNotInUseByUser()
    {

        $otherUser = $this->createUser();

        $linkUrl = 'http://pinda.test';
        $params = [
            'url' => $linkUrl
        ];
        $this->createLink($otherUser, $params);

        $link = $this->createLink($this->user);

        $url = $this->generateRouteForLink($link->id, 'update');
        $response = $this->makeRequest($url, 'PUT', $params, $this->user);

        $response->assertSuccessful();

        $data = $response->json('data');

        $this->assertNotNull($data);

        $this->assertArrayHasKey('id', $data);

        $id = $data['id'];

        /** @var Link $link */
        $link = Link::findOrFail($id);

        $this->assertEquals($params['url'], $link->url);
        $this->assertEquals($id, $link->id);
    }

    public function testUpdateLinkUrlNotChanged()
    {
        $linkUrl = 'http://pinda.test';

        $link = $this->createLink($this->user, [
            'url' => $linkUrl,
            'title' => 'Title',
            'description' => 'Description'
        ]);

        $params = [
            'url' => $linkUrl,
            'title' => 'New title',
            'description' => 'New description'
        ];

        $url = $this->generateRouteForLink($link->id, 'update');

        $response = $this->makeRequest($url, 'PUT', $params, $this->user);

        $response->assertSuccessful();

        /** @var Link $refreshedLink */
        $refreshedLink = Link::findOrFail($link->id);

        $this->assertEquals($refreshedLink->url, $params['url']);
        $this->assertEquals($refreshedLink->title, $params['title']);
        $this->assertEquals($refreshedLink->description, $params['description']);
    }

    public function testNewLinkValidationFailure()
    {
        $url = route('api.links.store');

        $response = $this->makeRequest($url, 'POST', [], $this->user);

        $response->assertJsonValidationErrors([
            'url'
        ]);
    }

    public function testNewLinkSuccess()
    {
        $url = route('api.links.store');

        $params = [
            'url' => $this->faker->url,
            'title' => $this->faker->company,
            'description' => $this->faker->text
        ];

        $response = $this->makeRequest($url, 'POST', $params, $this->user);

        $response->assertSuccessful();

        $data = $response->json('data');

        $this->assertNotNull($data);

        $this->assertArrayHasKey('id', $data);

        $id = $data['id'];

        /** @var Link $link */
        $link = Link::findOrFail($id);

        $this->assertEquals($params['url'], $link->url);
        $this->assertEquals($params['title'], $link->title);
        $this->assertEquals($params['description'], $link->description);
        $this->assertEquals($id, $link->id);
    }

    public function testNewLinkUrlAlreadyUsed()
    {
        $url = route('api.links.store');

        $linkUrl = 'http://pinda.test';
        $params = [
            'url' => $linkUrl
        ];
        $this->createLink($this->user, $params);

        $response = $this->makeRequest($url, 'POST', $params, $this->user);

        $response->assertJsonValidationErrors([
            'url'
        ]);
    }

    public function testNewLinkUrlNotInUseByUser()
    {
        $url = route('api.links.store');

        $otherUser = $this->createUser();

        $linkUrl = 'http://pinda.test';
        $params = [
            'url' => $linkUrl
        ];
        $this->createLink($otherUser, $params);

        $response = $this->makeRequest($url, 'POST', $params, $this->user);

        $response->assertSuccessful();

        $data = $response->json('data');

        $this->assertNotNull($data);

        $this->assertArrayHasKey('id', $data);

        $id = $data['id'];

        /** @var Link $link */
        $link = Link::findOrFail($id);

        $this->assertEquals($params['url'], $link->url);
        $this->assertEquals($id, $link->id);
    }

    /**
     * Generates a single link and returns it.
     * @see createLinks()
     *
     * @param User $user
     * @param array $attributes
     * @return Link
     */
    private function createLink(User $user, array $attributes = []): Link
    {
        return $this->createLinks($user, 1, $attributes)
            ->first();
    }

    /**
     * Generates the specified number of links and returns them.
     *
     * @param User $user - the user to associate the links with.
     * @param int $number - the number of links to generate. Default is 3.
     * @param array $attributes - attributes to be used. Would override default attributes.
     * @return Collection - returns a collection of links.
     */
    private function createLinks(User $user, $number = 3, array $attributes = []): Collection
    {
        return factory(Link::class, $number)
            ->make($attributes)
            ->each(function (Link $link) use ($user) {
                $link->user()->associate($user);
                $link->save();
            });
    }

    /**
     * Generates a route link based on the provided link and suffix.
     *
     * @param integer $linkId - the link id.
     * @param string $routeNameSuffix - the route name after "api.links.", i.e. "getLink"
     * @return string - the generated url.
     */
    private function generateRouteForLink(int $linkId, string $routeNameSuffix): string
    {
        return route('api.links.' . $routeNameSuffix, [
            'link' => $linkId
        ]);
    }
}