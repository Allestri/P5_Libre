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
        
    $app->get('/content', \App\Controllers\ContentController::class . ':getContent')->setName('content');
    
    // Inscription ( test )
    $app->get('/signup', \App\Controllers\MembersController::class . ':getFormInscripton')->setName('inscription');
    $app->post('/signup', \App\Controllers\MembersController:: class . ':postSignup')->setName('postInscription');
    
    //Login
    $app->post('/login', \App\Controllers\MembersController:: class . ':login')->setName('login');
    
    // Espace membre
    $app->get('/profile', \App\Controllers\MembersController:: class . ':displayProfile')->setName('profile');
    
    // Deconnexion
    $app->get('/deconnexion', \App\Controllers\MembersController:: class . ':logout')->setName('deconnexion');
    
    // Ajouter contenus ( test )
    $app->get('/add', \App\Controllers\ContentController ::class . ':getAddForm')->setName('ajouter');
    $app->post('/addcontent', \App\Controllers\ContentController ::class . ':addContent');
    
    // Upload de fichier
    $app->get('/upload', \App\Controllers\ContentController::class . ':getForm')->setName('upload');
    $app->post('/upload', \App\Controllers\ContentController::class . ':postUpload');
    
};
