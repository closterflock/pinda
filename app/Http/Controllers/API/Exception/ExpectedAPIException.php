<?php


namespace App\Http\Controllers\API\Exception;


class ExpectedAPIException extends \Exception
{
    /**
     * @var array
     */
    private $errors;

    /**
     * ExpectedAPIException constructor.
     *
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        parent::__construct('Missing parameters.');
        $this->errors = $errors;
    }

    /**
     * Retrieves the validation errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

}