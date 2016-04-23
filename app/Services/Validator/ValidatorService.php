<?php


namespace App\Services\Validator;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class ValidatorService implements ValidatorServiceInterface
{

    /**
     * {@inheritdoc}
     */
    public function validate(Controller $controller, Request $request, array $rules = null, array $messages = [], array $customAttributes = [])
    {
        if (!is_array($rules)) {
            $rules = $this->getDefaultRules();
        }

        $controller->validate($request, $rules, $messages, $customAttributes);
    }

}