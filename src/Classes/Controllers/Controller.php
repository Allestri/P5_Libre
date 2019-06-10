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
    
    
}