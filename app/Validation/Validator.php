<?php

namespace App\Validation;

use Respect\Validation\Exceptions\NestedValidationException;
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
                // Перевод для ошибок по email-у
                $exception->findMessages([
                    'email' => 'Некорректный формат электронной почты',
                    'emailAvailable' => 'Такой адрес электронной почты уже зарегистрирован',
                    'noWhitespace' => 'Использование символа пробел запрещено',
                    'notEmpty' => 'Поле {{name}} не может быть пустым',
                ]);
                $this->errors[$field] = $exception->getMessages();
            }
        }

        $_SESSION['errors'] = $this->errors;

        return $this;
    }

    public function failed()
    {
        return !empty($this->errors);
    }
}
