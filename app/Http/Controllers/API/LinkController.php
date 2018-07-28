<?php


namespace App\Http\Controllers\API;


use App\Http\Requests\LinkRequest;
use App\Http\Response\APIResponseFactory;
use App\Models\Link;
use App\Models\Repository\LinkRepository;
use App\Services\LinkService;
use App\Services\Validator\LinkValidator;
use Illuminate\Http\Request;
use Laracore\Factory\ModelFactory;

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
     * @param Link $link
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getLink(Link $link)
    {
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function deleteLink(Link $link)
    {
        $link->delete();
        return $this->successResponse();
    }

    /**
     * Saves a link.
     *
     * @route /api/v1/links/{link}
     * @method PUT
     * @param LinkRequest $request
     * @param Link $link
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updateLink(LinkRequest $request, Link $link)
    {
        $link->fill([
            'url' => $request->url,
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
     * @param LinkRequest $request
     * @param LinkService $service
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function newLink(LinkRequest $request, LinkService $service)
    {
        $link = $service->newLink(
            new ModelFactory(),
            $request->user(),
            $request->url,
            $request->title,
            $request->description,
            $request->get('tags', [])
        );

        return $this->idBackSuccess($link->id);
    }

}