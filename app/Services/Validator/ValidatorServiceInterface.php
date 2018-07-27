<?php


namespace App\Services\Validator;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

interface ValidatorServiceInterface
{
    /**
     * Retrieves the default rules.
     *
     * @return array
     */
    public function getDefaultRules();

    /**
     * Validates a request.
     *
     * @param Controller $controller
     * @param Request $request
     * @param array|null $rules
     * @param array $messages
     * @param array $customAttributes
     * @return mixed
     */
    public function validate(Controller $controller, Request $request, array $rules = null, array $messages = [], array $customAttributes = []);

}