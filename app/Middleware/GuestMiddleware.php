<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class GuestMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if ($this->container->auth->check()) {
            return $response->withStatus(302)->withRedirect($this->container->router->pathFor('home'));
        }
        return $next($request, $response);
    }
}