<?php
/**
 * Created by PhpStorm.
 * User: jamesspence
 * Date: 4/14/16
 * Time: 10:20 PM
 */

namespace App\Http\Controllers;


use Laracore\Factory\ModelFactory;
use App\Models\Link;
use App\Models\Repository\LinkRepository;
use App\Services\LinkService;
use App\Services\Validator\LinkValidator;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * @var LinkRepository
     */
    private $repository;
    /**
     * @var LinkValidator
     */
    private $validator;

    public function __construct(LinkRepository $repository, LinkValidator $validator)
    {
        $this->validator = $validator;
        $this->repository = $repository;
    }

    /**
     * The index.
     *
     * @route /
     * @method GET
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('links');
    }

    /**
     * The edit index.
     *
     * @route /link/{link}
     * @method GET
     * @param Link $link
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editIndex(Link $link)
    {
        return view('edit-links', [
            'link' => $link
        ]);
    }

    /**
     * Updates a link.
     *
     * @route /link/{link}/save
     * @method POST
     * @param Link $link
     * @param Request $request
     * @param LinkService $service
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateLink(Link $link, Request $request, LinkService $service)
    {
        $this->validator->validate($this, $request);

        if (!$this->validator->linkBelongsToUser($request->user(), $link)) {
            return redirect('/');
        }

        $service->saveLink($link, $request->url, $request->title, $request->description);

        return redirect('/');
    }

    /**
     * Deletes a link.
     *
     * @route /link/{link}/delete
     * @method GET
     * @param Link $link
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function deleteLink(Link $link, Request $request)
    {
        if (!$this->validator->linkBelongsToUser($request->user(), $link)) {
            return redirect('/');
        }

        $link->delete();

        return redirect('/');
    }

    /**
     * Creates a new link.
     *
     * @route /link/new
     * @method POST
     * @param Request $request
     * @param LinkService $service
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function newLink(Request $request, LinkService $service)
    {
        $this->validator->validate($this, $request);

        if ($this->validator->linkAlreadyExists(new LinkRepository(), $request->user(), $request->url)) {
            return redirect('/');
        }

        $service->newLink(
            new ModelFactory(),
            $request->user(),
            $request->url,
            $request->title,
            $request->description,
            $request->tags
        );

        return redirect('/');
    }

}