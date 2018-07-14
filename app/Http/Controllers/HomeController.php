<?php

namespace App\Http\Controllers;

use App\Models\Repository\LinkRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @route /
     * @method GET
     * @param Request $request
     * @param LinkRepository $repository
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, LinkRepository $repository)
    {
        return view('home', [
            'links' => $repository->getLinksForUser($request->user())
        ]);
    }
}
