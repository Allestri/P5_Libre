<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };
    
    // Twig
    $container['view'] = function ($container) {
        $dir = dirname(__DIR__);
        $view = new \Slim\Views\Twig($dir . '/templates/', [
            'debug' => true, // This line should enable debug mode
            'cache' => false //$dir . '/tmp/cache'
        ]);
        
        // Instantiate and add Slim specific extension
        $router = $container->get('router');
        $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
        $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));
        
        
        // Debugging extension i.e {{ dump() }}
        $view->addExtension(new \Twig\Extension\DebugExtension());
        
        return $view;
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };
    
    
    // Database
    $container['db'] = function ($c) {
        $db = $c['settings']['db'];
        $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
            $db['user'], $db['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    };
    
    // Directory upload
    $container['uploaded_directory'] = __DIR__ . '/../uploads';
    
    // Model data stored
    $container['contentModel'] = new \App\Models\ContentModel($container);
    // Model members & registration
    $container['membersModel'] = new \App\Models\MembersModel($container);
    
    
};
