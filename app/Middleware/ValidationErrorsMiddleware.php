<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class ValidationErrorsMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors'] ?? null);
        unset($_SESSION['errors']);
        return $next($request, $response);
    }
}