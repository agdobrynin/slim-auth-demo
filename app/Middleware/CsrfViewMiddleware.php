<?php

namespace App\Middleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class CsrfViewMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        /** @var \Slim\Csrf\Guard $csrf */
        $csrf = $this->container->csrf;
        $this->container->view->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="' . $csrf->getTokenNameKey() . '" value="' . $csrf->getTokenName() . '">
                <input type="hidden" name="' . $csrf->getTokenValueKey() . '" value="' . $csrf->getTokenValue() . '">
                ',
        ]);
        return $next($request, $response);
    }
}