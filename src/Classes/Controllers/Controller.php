<?php

namespace App\Controllers;

class Controller
{
    
    protected $container;
    
    // Passes the DIC to get the model.
    function __construct($container)
    {
        $this->container = $container;
    }
    
    public function redirect($response, $name){
        return $response->withStatus(302)->withHeader('Location', $this->container->router->pathFor($name));
    }
    
    
}