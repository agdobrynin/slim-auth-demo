<?php

namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;

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
            'email' => v::email()
                ->emailAvailable(),
            'name' => v::regex('/([\-а-яa-z\s]+)/i')
                ->length(3, null)
                ->setTemplate('Имя - обязательное поледолжно содержать только символы (не менее 3х)'),
            'password' => v::notEmpty()
                ->noWhitespace()
                ->length(6, null)
                ->setTemplate('Пароль - не менее 6-ти сиволов, недолжно содержать пробелов'),
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
