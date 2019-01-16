<?php

namespace App\Validation;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as Respect;
use Slim\Http\Request;

class Validator
{
    protected $errors;

    public function validate(Request $request, array $rules)
    {
        $this->errors = null;
        foreach ($rules as $field => $rule) {
            try {
                if (empty($rule->getName())) {
                    $rule->setName(ucfirst($field));
                }
                $rule->assert($request->getParam($field));
            } catch (NestedValidationException $exception) {
                $this->errors[$field] = $exception->getMainMessage();
            }
        }
        return $this;
    }

    public function failed()
    {
        return !empty($this->errors);
    }
}
