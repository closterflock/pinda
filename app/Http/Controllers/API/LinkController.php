<?php


namespace App\Http\Controllers\API;


use App\Http\Response\APIResponseFactory;
use App\Models\Factory\ModelFactory;
use App\Models\Link;
use App\Models\Repository\LinkRepository;
use Illuminate\Http\Request;

class LinkController extends APIController
{
    /**
     * @var LinkRepository
     */
    private $repository;

    public function __construct(APIResponseFactory $responseFactory, LinkRepository $repository)
    {
        parent::__construct($responseFactory);
        $this->repository = $repository;
    }

    /**
     * Retrieves a list of links.
     *
     * @route /api/v1/links
     * @method GET
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getLinks(Request $request)
    {
        return $this->successResponse('Success', [
            'links' => $this->repository->getLinksForUser($request->user())
        ]);
    }

    /**
     * Retrieves a link.
     *
     * @route /api/v1/links/{link}
     * @method GET
     * @param Request $request
     * @param Link $link
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getLink(Request $request, Link $link)
    {
        if ($link->user_id !== $request->user()->id) {
            return $this->belongsToOtherError();
        }

        return $this->successResponse('Success', [
            'link' => $link
        ]);
    }

    /**
     * Deletes a link.
     *
     * @route /api/v1/links/{link}
     * @method DELETE
     * @param Link $link
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function deleteLink(Link $link, Request $request)
    {
        if ($link->user_id !== $request->user()->id) {
            return $this->belongsToOtherError();
        }

        $link->delete();
        return $this->successResponse();
    }

    /**
     * Saves a link.
     *
     * @route /api/v1/links/{link}
     * @method PUT
     * @param Request $request
     * @param Link $link
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updateLink(Request $request, Link $link)
    {
        $this->validate($request, [
            'url' => 'required'
        ]);

        if ($link->user_id !== $request->user()->id) {
            return $this->belongsToOtherError();
        }

        $link->fill([
            'title' => $request->title,
            'description' => $request->description
        ]);
        $link->save();

        return $this->idBackSuccess($link->id);
    }

    /**
     * Creates a new link.
     *
     * @route /api/v1/links/new
     * @method PUT
     * @param Request $request
     * @param ModelFactory $factory
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function newLink(Request $request, ModelFactory $factory)
    {
        $this->validate($request, [
            'url' => 'required'
        ]);

        $link = $this->repository->getLinkForUserByUrl($request->user(), $request->url);
        if (isset($link)) {
            return $this->resourceExistsError();
        }

        /** @var Link $link */
        $link = $factory->make([
            'url' => $request->url,
            'title' => $request->title,
            'description' => $request->description
        ], [
            'user' => $request->user()
        ]);

        return $this->idBackSuccess($link->id);
    }

}