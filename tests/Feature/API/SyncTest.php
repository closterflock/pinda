<?php


namespace Tests\Feature\API;


use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class SyncTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;

    /**
     * @var User
     */
    private $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function testSyncNoUser()
    {
        $response = $this->sync();

        $response->assertForbidden();
    }

    public function testSyncInvalidTimestamp()
    {
        $response = $this->syncWithUser('asdf');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'timestamp'
        ]);
    }

    public function testSyncNoTimestamp()
    {
        $user = $this->user;

        /** @var Collection $tags */
        $tags = factory(Tag::class, 5)
            ->states('previous')
            ->make()
            ->each(function (Tag $tag) use ($user) {
                $tag->user()->associate($user);
                $tag->save();
            });

        factory(Link::class, 5)
            ->states('previous')
            ->make()
            ->each(function (Link $link) use ($user, $tags) {
                $randomTag = $tags->get(rand(0, 4));
                $link->user()->associate($user);
                $link->save();
                $link->tags()->save($randomTag);
            });

        $response = $this->syncWithUser();

        $response->assertSuccessful();

        $data = $response->json('data');

        $this->assertSyncData($data, [
            'links',
            'tags',
            'link_tags'
        ]);

        $this->assertCount(5, $data['links']);
        $this->assertCount(5, $data['tags']);
        $this->assertCount(5, $data['link_tags']);
    }

    public function testSyncDeletedLinks()
    {
        $user = $this->user;

        factory(Link::class, 5)
            ->states('previous')
            ->make()
            ->each(function (Link $link) use ($user) {
                $link->user()->associate($user);
                $link->save();
            });

        /** @var Link $link */
        $link = factory(Link::class)
            ->make([
                'deleted_at' => Carbon::now()
            ]);

        $link->user()->associate($user);
        $link->save();

        $linkId = $link->id;

        $response = $this->sync(urlencode(Carbon::now()->subHour()->toDateTimeString()), $user);

        $response->assertSuccessful();
        $data = $response->json('data');

        $this->assertSyncData($data, [
            'deleted_links'
        ]);

        $deletedLinks = $data['deleted_links'];
        $this->assertCount(1, $deletedLinks);
        $this->assertEquals($linkId, $deletedLinks[0]['id']);
    }

    public function testSyncDeletedTag()
    {
        $user = $this->user;

        $tag = factory(Tag::class)
            ->make();
        $tag->user()->associate($user);
        $tag->save();

        /** @var Link $link */
        $link = factory(Link::class)
            ->states('previous')
            ->make();
        $link->user()->associate($user);
        $link->save();
        $link->tags()->save($tag);

        $timestamp = Carbon::now()->subMinute();

        $tag->delete();

        $response = $this->sync(urlencode($timestamp->toDateTimeString()), $user);
        $response->assertSuccessful();

        $data = $response->json('data');

        $this->assertSyncData($data, [
            'deleted_tags'
        ]);

        $this->assertCount(1, $data['deleted_tags']);

        $responseTag = $data['deleted_tags'][0];

        $this->assertEquals($tag->id, $responseTag['id']);
    }

    private function syncWithUser($timestampString = null)
    {
        return $this->sync($timestampString, $this->user);
    }

    private function sync($timestampString = null, User $user = null): TestResponse
    {
        $url = route('api.sync');

        if (!is_null($timestampString)) {
            $url.= '?timestamp=' . $timestampString;
        }

        if (is_null($user)) {
            return $this->getJson($url);
        }

        return $this->actingAs($user)
            ->getJson($url);
    }

    private function assertSyncData($data, array $nonEmptyKeys = [])
    {
        $this->assertNotNull($data);

        $allKeys = [
            'links',
            'tags',
            'link_tags',
            'deleted_links',
            'deleted_tags'
        ];

        foreach ($allKeys as $allKey) {
            $this->assertArrayHasKey($allKey, $data);
        }
        $emptyKeys = array_diff($allKeys, $nonEmptyKeys);

        foreach ($emptyKeys as $emptyKey) {
            $this->assertEmpty($data[$emptyKey]);
        }

        foreach ($nonEmptyKeys as $nonEmptyKey) {
            $this->assertNotEmpty($data[$nonEmptyKey]);
        }
    }
}