<?php
/**
 * Created by PhpStorm.
 * User: jamesspence
 * Date: 4/14/16
 * Time: 10:20 PM
 */

namespace App\Http\Controllers;


use App\Models\Factory\ModelFactory;
use App\Models\Link;
use App\Models\Repository\LinkRepository;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * @var LinkRepository
     */
    private $repository;

    public function __construct(LinkRepository $repository)
    {
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateLink(Link $link, Request $request)
    {
        $this->validate($request, [
            'url' => ['required', 'url']
        ]);

        $link->load('user');
        if ($link->user->id !== $request->user()->id) {
            return redirect('/');
        }

        $link->fill([
            'url' => $request->url,
            'title' => $request->title,
            'description' => $request->description
        ]);

        $link->save();
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
        $link->load('user');
        if ($link->user->id !== $request->user()->id) {
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
     * @param ModelFactory $factory
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function newLink(Request $request, ModelFactory $factory)
    {
        $this->validate($request, [
            'url' => ['required', 'url']
        ]);

        $factory->setRepository($this->repository);

        $link = $this->repository->getLinkForUserByUrl($request->user(), $request->url);
        if (isset($link)) {
            return redirect('/');
        }

        $factory->make([
            'url' => $request->url,
            'title' => $request->title,
            'description' => $request->description
        ], [
            'user' => $request->user()
        ]);

        return redirect('/');
    }

}