<?php

namespace App\Middleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class AuthMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$this->container->auth->check()) {
            $this->container->flash->addMessage('error', 'Пройдите авторизацию для досутпа к этому разделу сайта.');
            return $response->withStatus(302)->withRedirect($this->container->router->pathFor('auth.signin'));
        }
        return $next($request, $response);
    }
}