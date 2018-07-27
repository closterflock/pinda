<?php


namespace App\Http\Response;
use Illuminate\Http\Response;


/**
 * An interface that allows a class to be converted to a response.
 *
 * Interface ToResponse
 * @package App\Http\Response
 */
interface ToResponse
{

    /**
     * Converts an object to a response.
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function toResponse();

}