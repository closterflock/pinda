<?php
/**
 * Created by PhpStorm.
 * User: jamesspence
 * Date: 4/14/16
 * Time: 10:20 PM
 */

namespace App\Http\Controllers;


use App\Models\Link;
use App\Models\Repository\LinkRepository;
use App\Services\LinkService;
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
     * Creates a new link, or saves an already existing one.
     *
     * @method POST
     * @param Request $request
     * @param LinkService $service
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function newLink(Request $request, LinkService $service)
    {
        $this->validate($request, [
            'url' => ['required', 'url']
        ]);

        $service->saveLink(
            $request->user(),
            $request->url,
            $request->title,
            $request->description
        );

        return redirect('/');
    }

}