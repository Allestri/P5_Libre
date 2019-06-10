<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    
    $container = $app->getContainer();
    /*
    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    }); */
    
    
    $app->get('/', \App\Controllers\ContentController::class .':home')->setName('home');
        
    $app->get('/content', \App\Controllers\ContentController::class . ':getContent')->setName('contact');
        
    // Upload de fichier
    $app->get('/upload', \App\Controllers\ContentController::class . ':getForm')->setName('upload');
    $app->post('/upload', \App\Controllers\ContentController::class . ':postUpload');
    
};
