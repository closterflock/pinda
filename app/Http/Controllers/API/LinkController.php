<?php


namespace App\Http\Controllers\API;


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
    /**
     * @var LinkValidator
     */
    private $validator;

    public function __construct(APIResponseFactory $responseFactory, LinkRepository $repository, LinkValidator $validator)
    {
        parent::__construct($responseFactory);
        $this->repository = $repository;
        $this->validator = $validator;
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
     * Retrieves a list of links based on search.
     *
     * @route /api/v1/links/search
     * @method GET
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getLinksForSearch(Request $request)
    {
        return $this->successResponse('Success', [
            'links' => $this->repository->getLinksForSearch($request->term)
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
        if (!$this->validator->linkBelongsToUser($request->user(), $link)) {
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
        if (!$this->validator->linkBelongsToUser($request->user(), $link)) {
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
        if (!$this->validator->linkBelongsToUser($request->user(), $link)) {
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
     * @param LinkService $service
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function newLink(Request $request, LinkService $service)
    {
        $this->validator->validate($this, $request);

        if ($this->validator->linkAlreadyExists($this->repository, $request->user(), $request->url)) {
            return $this->resourceExistsError();
        }

        $link = $service->newLink(
            new ModelFactory(),
            $request->user(),
            $request->url,
            $request->title,
            $request->description
        );

        return $this->idBackSuccess($link->id);
    }

}