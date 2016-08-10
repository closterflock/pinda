<?php


namespace App\Http\Controllers\API;


use App\Models\Repository\TagRepository;
use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends APIController
{

    /**
     * Creates a new tag.
     *
     * @method PUT
     * @route /api/v1/tags/new
     * @param Request $request
     * @param TagService $service
     * @return \Illuminate\Http\Response
     */
    public function newTag(Request $request, TagService $service)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        $tag = $service->firstOrCreateTag($request->user(), $request->name);

        return $this->successResponse('Success', ['tag' => $tag->id]);
    }

    /**
     * Retrieves all tags for a user.
     *
     * @method GET
     * @route /api/v1/tags
     * @param Request $request
     * @param TagRepository $repository
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTags(Request $request, TagRepository $repository)
    {
        return $this->successResponse('Success', [
            'tags' => $repository->getAllTagsForUser($request->user())
        ]);
    }
}