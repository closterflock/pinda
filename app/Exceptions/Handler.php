<?php

namespace App\Exceptions;

use App\Http\Controllers\API\Exception\ExpectedAPIException;
use App\Http\Controllers\API\Exception\UnexpectedAPIException;
use App\Http\Response\APIResponseFactory;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * @var APIResponseFactory
     */
    private $factory;

    public function __construct(LoggerInterface $log, APIResponseFactory $factory)
    {
        $this->factory = $factory;
        parent::__construct($log);
    }

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
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
