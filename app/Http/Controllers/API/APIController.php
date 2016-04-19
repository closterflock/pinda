<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
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

    /**
     * Creates a success response.
     *
     * @param string $message
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function successResponse($message = 'Success', $data = [], $headers = [])
    {
        return $this->responseFactory->make('success', $message, $data, 200, $headers);
    }

}