<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;

class Controller
{
    
    protected $container;
    
    // Passes the DIC to get the model.
    function __construct($container)
    {
        $this->container = $container;
    }
    
    public function render(ResponseInterface $response, $file, $params = []){
        
        $this->container->view->render($response, $file, $params);
    }
    
    public function redirect($response, $name){
        return $response->withStatus(302)->withHeader('Location', $this->container->router->pathFor($name));
    }
    
    
    public function flashNow($message, $type ='success')
    {
        if(!isset($_SESSION['flash'])){
            $_SESSION['flash'] = [];
        }
        return $_SESSION['flash'][$type] = $message;
    }
    
    public function flash($message, $type ='success')
    {
        if(!isset($_SESSION['flash'])){
            $_SESSION['flash'] = [];
        }
        return $_SESSION['flash'][$type] = $message;
    }
    
    
}