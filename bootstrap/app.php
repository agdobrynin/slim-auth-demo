<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'mysql',
            'host' => '172.16.238.12',
            'database' => 'bani',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
    ]
]);

$container = $app->getContainer();

$capsule = new Illuminate\Database\Capsule\Manager();
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Register provider
$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

$container['HomeController'] = function ($container) {
    return new App\Controllers\HomeController($container);
};

$container['validator'] = function ($container) {
    \Respect\Validation\Validator::with('\\App\\Validation\\Rules');
    return new Awurth\SlimValidation\Validator();
};

$container['AuthController'] = function ($container) {
    return new App\Controllers\Auth\AuthController($container);
};

$container['auth'] = function ($container) {
    return new App\Aauth\Auth();
};

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages();
};

$container['view'] = function ($container) {
    $view = new Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);

    $view->addExtension(new Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->addExtension(
        new Awurth\SlimValidation\ValidatorExtension($container['validator'])
    );

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
};

$container['csrf'] = function ($container) {
    return new \Slim\Csrf\Guard;
};

$app->add(new App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);

require __DIR__ . '/../app/routes.php';
