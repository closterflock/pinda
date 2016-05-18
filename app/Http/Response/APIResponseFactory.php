<?php


namespace App\Http\Response;


use Illuminate\Http\Response;

class APIResponseFactory
{

    /**
     * Creates a new APIResponse, and returns it in Response form.
     *
     * @param string $status
     * @param string $message
     * @param array $data
     * @param int $httpStatus
     * @param array $headers
     * @return Response
     */
    public function make($status = 'success', $message = 'Success', $data = [], $httpStatus = 200, $headers = [])
    {
        return (new APIResponse($status, $message, $data, $httpStatus, $headers))->toResponse();
    }

}