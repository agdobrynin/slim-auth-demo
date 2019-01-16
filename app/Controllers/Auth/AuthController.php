<?php

namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as Validator;

class AuthController extends Controller
{
    public function getSignUp(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp(Request $request, Response $response)
    {
        /** @var  App\Validation\Validator $validation */
        $validation = $this->validator->validate($request, [
            'email' => Validator::email()->setTemplate('Электронная почта обязательное поле'),
            'name' => Validator::notEmpty()->alpha('а-яА-ЯёЁ')->setTemplate('Имя - обязательное поледолжно содержать только символы'),
            'password' => Validator::noWhitespace()->notEmpty()->setTemplate('Пароль - обязательное и недолжно содержать пробелов'),
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        User::create([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

        return $response->withRedirect($this->router->pathFor('home'));
    }
}