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

    /**
     * Generates a single tag and returns it.
     * @see createTags()
     *
     * @param User $user
     * @return Tag
     */
    private function createTag(User $user): Tag
    {
        return $this->createTags($user, 1)
            ->first();
    }

    /**
     * Generates the specified number of tags and returns them.
     *
     * @param User $user - the user to associate the tags with.
     * @param int $number - the number of tags to generate. Default is 3.
     * @return Collection - returns a collection of tags.
     */
    private function createTags(User $user, $number = 3): Collection
    {
        return factory(Tag::class, $number)
            ->make()
            ->each(function (Tag $tag) use ($user) {
                $tag->user()->associate($user);
                $tag->save();
            });
    }

}