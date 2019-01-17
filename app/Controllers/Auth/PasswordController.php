<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Respect\Validation\Validator as V;

class PasswordController extends Controller
{
    public function getChange(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/change/password.twig');
    }

    public function postChange(Request $request, Response $response)
    {
        $validator = $this->validator->validate($request, [
            'password_old' => [
                'rules' => V::matchesPassword($this->auth->user()->password),
                'message' => 'Текущий пароль неверный!'
            ],
            'password' => [
                'rules' => V::length(6, null)
                            ->noWhitespace(),
                'messages' => [
                    'length' => 'Длина пароля не менее 6 символов',
                    'noWhitespace' => 'Пароль содержит символ "пробел"',
                ]
            ],
            'confirm_password' => [
                'rules' => V::equals($request->getParam('password')),
                'message' => 'Пароли не совпадают',
            ],
        ]);

        // УПС - валадация сломана :-(
        if (!$validator->isValid()) {
            return $this->view->render($response, 'auth/change/password.twig');
        }

        $this->auth->user()->setPassword($request->getParam('password'));

        $this->flash->addMessage('info', 'Пароль успешно изменен');
        return $response->withStatus(302)->withRedirect($this->router->pathFor('home'));
    }
}
