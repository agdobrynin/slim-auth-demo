<?php

namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as V;

class AuthController extends Controller
{
    public function getSignUp(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp(Request $request, Response $response)
    {

        $validator = $this->validator->validate($request, [
            'email' => [
                'rules' => V::notBlank()->email()->emailUnique(),
                'messages' => [
                    'notBlank' => 'Электронная почта обязательный параметр',
                    'email' => 'Неверный формат электронной почты {{name}}',
                    'emailUnique' => '{{name}} уже используется на сайте',
                ]
            ],
            'name' => [
                'rules' => V::length(6, 100)->regex('/([а-яa-z]+)/i'),
                'messages' => [
                    'length' => 'Имя должно быть от 6 до 100 символов',
                    'regex' => 'Имя может содержать русские или латинские буквы и пробел',
                ]
            ],
            'password' => [
                'rules' => V::length(6, null)->noWhitespace(),
                'messages' => [
                    'length' => 'Длина пароля не менее 6 символов',
                    'noWhitespace' => 'Пароль содержит символ "пробел"',
                ]
            ],
            'confirm_password' =>[
                'rules' => V::equals($request->getParam('password')),
                'message' => 'Пароли не совпадают',
            ],
        ]);

        // УПС - валадация сломана :-(
        if (!$validator->isValid()) {
            return $this->view->render($response, 'auth/signup.twig');
        }

        // Прошли валидацию успешно!

        User::create([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

        return $response->withRedirect($this->router->pathFor('home'));
    }
}
