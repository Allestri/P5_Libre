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
    
    
}