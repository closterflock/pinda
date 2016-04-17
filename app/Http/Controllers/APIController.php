<?php

namespace App\Http\Controllers;


use App\Http\Response\APIResponseFactory;
use Illuminate\Http\Request;

abstract class APIController extends Controller
{
    /**
     * @var APIResponseFactory
     */
    protected $responseFactory;

    public function __construct(APIResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFailedValidationResponse(Request $request, array $errors)
    {
        return $this->responseFactory->make('error', 'Missing parameters.', $errors, 400);
    }

}