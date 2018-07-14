<?php

namespace App\Exceptions;

use App\Http\Controllers\API\Exception\ExpectedAPIException;
use App\Http\Controllers\API\Exception\UnexpectedAPIException;
use App\Http\Response\APIResponseFactory;
use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * @var APIResponseFactory
     */
    private $factory;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function __construct(Container $container, APIResponseFactory $factory)
    {
        parent::__construct($container);
        $this->factory = $factory;
    }

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ExpectedAPIException) {
            return $this->factory->make(
                'error',
                'Missing parameters.',
                $e->getErrors(),
                422
            );
        }
        if ($e instanceof UnexpectedAPIException || $request->is('api/*', 'token')) {
            $data = [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTrace()
            ];
            return $this->factory->make(
                'error',
                'Uncaught Exception: ' . $e->getMessage(),
                $data,
                400
            );
        }

        return parent::render($request, $e);
    }
}
