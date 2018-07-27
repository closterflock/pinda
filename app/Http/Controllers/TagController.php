<?php


namespace App\Http\Controllers;


use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{

    /**
     * Creates a new tag.
     *
     * @method PUT
     * @route /tags/new
     * @param Request $request
     * @param TagService $service
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function newTag(Request $request, TagService $service)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        $service->firstOrCreateTag($request->user(), $request->name);

        return redirect('/');
    }

}