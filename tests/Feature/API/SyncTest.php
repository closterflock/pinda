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

    public function testSyncInvalidTimestamp()
    {
        $response = $this->sync('asdf');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'timestamp'
        ]);
    }
    
    public function testSyncNoTimestamp()
    {
        /** @var User $user */
        $user = factory(User::class)
            ->create();

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

        $response = $this->sync(null, $user);

        $response->assertSuccessful();

        $data = $response->json('data');

        $this->assertNotNull($data);
        $this->assertArrayHasKey('links', $data);
        $this->assertArrayHasKey('tags', $data);
        $this->assertArrayHasKey('link_tags', $data);
        $this->assertArrayHasKey('deleted_links', $data);
        $this->assertArrayHasKey('deleted_tags', $data);

        $this->assertCount(5, $data['links']);
        $this->assertCount(5, $data['tags']);
        $this->assertCount(5, $data['link_tags']);
    }

    public function testSyncDeletedLinks()
    {
        /** @var User $user */
        $user = factory(User::class)
            ->create();

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
        $this->assertNotNull($data);

        $this->assertArrayHasKey('deleted_links', $data);
        $deletedLinks = $data['deleted_links'];
        $this->assertNotNull($deletedLinks);
        $this->assertCount(1, $deletedLinks);
        $this->assertEquals($linkId, $deletedLinks[0]['id']);
    }

    public function testSyncDeletedTagNoLinkTagProvided()
    {
        //TODO generate link
        //TODO generate tag
        //TODO generate relation between the two
        //TODO delete tag
        //TODO ensure link_tag is not returned
        $this->stub();
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
}