<?php
/**
 * Created by PhpStorm.
 * User: jamesspence
 * Date: 4/14/16
 * Time: 10:20 PM
 */

namespace App\Http\Controllers;


use App\Models\Factory\ModelFactory;
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

    public function index()
    {
        return view('links');
    }

    public function saveLink(Request $request, ModelFactory $factory)
    {
        $this->validate($request, [
            'url' => ['required', 'url']
        ]);

        $user = $request->user();
        $factory->setRepository($this->repository);

        $factory->make([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url
        ], [
            'user' => $user
        ]);

        return redirect('/');
    }

}