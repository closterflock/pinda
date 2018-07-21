<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\API\Exception\ExpectedAPIException;
use App\Http\Controllers\API\Exception\UnexpectedAPIException;
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
     * Creates a success response.
     *
     * @param string $message
     * @param array $data
     * @param int $httpStatus
     * @param array $headers
     * @return \Illuminate\Http\Response
     */
    public function successResponse($message = 'Success', $data = [], $httpStatus = 200, $headers = [])
    {
        return $this->responseFactory->make('success', $message, $data, $httpStatus, $headers);
    }

    /**
     * Creates an error response.
     *
     * @param string $message
     * @param array $data
     * @param int $httpStatus
     * @param array $headers
     * @return \Illuminate\Http\Response
     */
    public function errorResponse($message = 'Error', $data = [], $httpStatus = 400, $headers = [])
    {
        return $this->responseFactory->make('error', $message, $data, $httpStatus, $headers);
    }

    /**
     * Creates a does not belong to user error.
     *
     * @param string $message
     * @param array $data
     * @param int $httpStatus
     * @param array $headers
     * @return \Illuminate\Http\Response
     */
    public function belongsToOtherError($message = 'This resource does not belong to the user.', $data = [], $httpStatus = 403, $headers = [])
    {
        return $this->errorResponse($message, $data, $httpStatus, $headers);
    }

    /**
     * Creates a resource exists error.
     *
     * @param string $message
     * @param array $data
     * @param int $httpStatus
     * @param array $headers
     * @return \Illuminate\Http\Response
     */
    public function resourceExistsError($message = 'Resource already exists.', $data = [], $httpStatus = 409, $headers = [])
    {
        return $this->errorResponse($message, $data, $httpStatus, $headers);
    }

    /**
     * Returns an id success response.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function idBackSuccess($id)
    {
        return $this->successResponse('Success', [
            'id' => $id
        ]);
    }

    /**
     * Returns a resource not found error.
     *
     * @return \Illuminate\Http\Response
     */
    public function resourceNotFoundError()
    {
        return $this->errorResponse('Resource not found', [], 404);
    }

}