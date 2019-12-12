<?php

use Slim\App;

return function (App $app) {
    // e.g: $app->add(new \Slim\Csrf\Guard);
    $container = $app->getContainer();
    
    // Flash messages
    $app->add(new \App\Middlewares\FlashMiddleware($container->view->getEnvironment()));
    // Old Values Middleware
    $app->add(new \App\Middlewares\OldValuesMiddleware($container->view->getEnvironment()));
    // Session Middleware
    $app->add(new \App\Middlewares\SessionMiddleware($container->view->getEnvironment()));
    
};
