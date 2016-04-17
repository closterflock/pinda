<?php


namespace App\Http\Response;


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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function make($status = 'success', $message = 'Success', $data = [], $httpStatus = 200, $headers = [])
    {
        return (new APIResponse($status, $message, $data, $httpStatus, $headers))->toResponse();
    }

}