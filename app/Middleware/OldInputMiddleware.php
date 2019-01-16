<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class OldInputMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $this->container->view->getEnvironment()->addGlobal('old', $_SESSION['old'] ?? null);
        $_SESSION['old'] = $request->getParams();
        return $next($request, $response);
    }
}