<?php


namespace Tests\Feature\API;


use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagCrudTest extends TestCase
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

    public function testGetTagsSuccess()
    {
        /** @var Collection $tags */
        $tags = $this->createTags($this->user);
        $tagIds = $tags->pluck('id');

        $otherUser = $this->createUser();
        $this->createTags($otherUser);

        $response = $this->makeRequest(route('api.tags.getTags'), 'GET', [], $this->user);

        $response->assertSuccessful();
        $data = $response->json('data');

        $this->assertNotNull($data);

        $this->assertArrayHasKey('tags', $data);

        $responseTags = $data['tags'];
        $this->assertCount(3, $responseTags);
        foreach ($responseTags as $responseTag) {
            $this->assertEquals($this->user->id, $responseTag['user_id']);
            $this->assertContains($responseTag['id'], $tagIds);
        }
    }

    public function testCreateTagValidationFailure()
    {
        $url = route('api.tags.store');

        $response = $this->makeRequest($url, 'POST', [], $this->user);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'name'
        ]);
    }

    public function testCreateTagSuccess()
    {
        $url = route('api.tags.store');

        $params = [
            'name' => $this->faker->word
        ];

        $response = $this->makeRequest($url, 'POST', $params, $this->user);

        $response->assertSuccessful();

        $data = $response->json('data');

        $this->assertNotNull($data);

        $this->assertArrayHasKey('id', $data);

        /** @var Tag $tag */
        $tag = Tag::findOrFail($data['id']);

        $this->assertEquals($this->user->id, $tag->user_id);
        $this->assertEquals($params['name'], $tag->name);
    }

    public function testCreateTagAlreadyExisting()
    {
        $tag = $this->createTag($this->user);

        $url = route('api.tags.store');

        $params = [
            'name' => $tag->name
        ];

        $response = $this->makeRequest($url, 'POST', $params, $this->user);

        $response->assertSuccessful();

        $data = $response->json('data');

        $this->assertNotNull($data);

        $this->assertArrayHasKey('id', $data);

        /** @var Tag $responseTag */
        $responseTag = Tag::findOrFail($data['id']);

        $this->assertEquals($this->user->id, $responseTag->user_id);
        $this->assertEquals($tag->id, $responseTag->id);
        $this->assertEquals($tag->name, $tag->name);
    }

    public function testGetTagNotOwnedByUser()
    {
        $otherUser = $this->createUser();
        $tag = $this->createTag($otherUser);

        $tagId = $tag->id;
        $url = $this->createTagRoute('getTag', $tagId);

        $response = $this->makeRequest($url, 'GET', [], $this->user);

        $response->assertForbidden();
    }

    public function testGetTagNotFound()
    {
        $url = $this->createTagRoute('getTag', 9999);

        $response = $this->makeRequest($url, 'GET', [], $this->user);
        $response->assertNotFound();
    }

    public function testGetTagSuccess()
    {
        $tag = $this->createTag($this->user);

        $tagId = $tag->id;

        $url = $this->createTagRoute('getTag', $tagId);

        $response = $this->makeRequest($url, 'GET', [], $this->user);

        $response->assertSuccessful();

        $data = $response->json('data');

        $this->assertNotNull($data);

        $this->assertArrayHasKey('tag', $data);
        $tagResponse = $data['tag'];
        $this->assertNotNull($tagResponse);
        $this->assertArrayHasKey('user_id', $tagResponse);
        $this->assertArrayHasKey('id', $tagResponse);

        $this->assertEquals($tagId, $tagResponse['id']);
        $this->assertEquals($this->user->id, $tagResponse['user_id']);
    }

    public function testUpdateTagValidationFailed()
    {
        $tag = $this->createTag($this->user);

        $url = $this->createTagRoute('update', $tag->id);

        $response = $this->makeRequest($url, 'PUT', [], $this->user);

        $response->assertJsonValidationErrors([
            'name'
        ]);
    }

    public function testUpdateTagNotFound()
    {
        $url = $this->createTagRoute('update', 9999);

        $response = $this->makeRequest($url, 'PUT', [], $this->user);

        $response->assertNotFound();
    }

    public function testUpdateTagNotOwnedByUser()
    {
        $otherUser = $this->createUser();

        $tag = $this->createTag($otherUser);

        $url = $this->createTagRoute('update', $tag->id);

        $response = $this->makeRequest($url, 'PUT', ['name' => 'New Name'], $this->user);

        $response->assertForbidden();
    }

    public function testUpdateTagSuccess()
    {
        $tag = $this->createTag($this->user, [
            'name' => 'Tag Name'
        ]);

        $url = $this->createTagRoute('update', $tag->id);

        $params = [
            'name' => 'New Name'
        ];

        $response = $this->makeRequest($url, 'PUT', $params, $this->user);

        $response->assertSuccessful();

        $data = $response->json('data');

        $this->assertNotNull($data);

        $this->assertArrayHasKey('id', $data);

        $this->assertEquals($tag->id, $data['id']);

        /** @var Tag $refreshedTag */
        $refreshedTag = Tag::findOrFail($tag->id);

        $this->assertEquals($refreshedTag->name, $params['name']);
    }

    public function testDeleteTagNotOwnedByUser()
    {
        $otherUser = $this->createUser();

        $tag = $this->createTag($otherUser);

        $url = $this->createTagRoute('delete', $tag->id);

        $response = $this->makeRequest($url, 'DELETE', [], $this->user);

        $response->assertForbidden();
    }

    public function testDeleteTagNotFound()
    {
        $url = $this->createTagRoute('delete', 9999);

        $response = $this->makeRequest($url, 'DELETE', [], $this->user);

        $response->assertNotFound();
    }

    public function testDeleteTagSuccess()
    {
        $tag = $this->createTag($this->user);

        $url = $this->createTagRoute('delete', $tag->id);

        $response = $this->makeRequest($url, 'DELETE', [], $this->user);

        $response->assertSuccessful();

        /** @var Tag $deletedTag */
        $deletedTag = Tag::withTrashed()
            ->findOrFail($tag->id);

        $this->assertTrue($deletedTag->trashed());
    }

    private function createTagRoute($routePrefix, $tagId): string
    {
        return route('api.tags.' . $routePrefix, [
            'tag' => $tagId
        ]);
    }

    /**
     * Generates a single tag and returns it.
     * @see createTags()
     *
     * @param User $user
     * @param array $attributes - the list of overridden attributes.
     * @return Tag
     */
    private function createTag(User $user, array $attributes = []): Tag
    {
        return $this->createTags($user, 1, $attributes)
            ->first();
    }

    /**
     * Generates the specified number of tags and returns them.
     *
     * @param User $user - the user to associate the tags with.
     * @param int $number - the number of tags to generate. Default is 3.
     * @param array $attributes - the list of overridden attributes.
     * @return Collection - returns a collection of tags.
     */
    private function createTags(User $user, $number = 3, array $attributes = []): Collection
    {
        return factory(Tag::class, $number)
            ->make($attributes)
            ->each(function (Tag $tag) use ($user) {
                $tag->user()->associate($user);
                $tag->save();
            });
    }

}