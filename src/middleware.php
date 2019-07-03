<?php

use Slim\App;

return function (App $app) {
    // e.g: $app->add(new \Slim\Csrf\Guard);
    $container = $app->getContainer();
    
    // Flash messages
    $app->add(new \App\Middlewares\FlashMiddleware($container->view->getEnvironment()));
    // CSRF
    //$app->add($container->csrf);
};
