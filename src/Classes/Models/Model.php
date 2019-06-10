<?php

namespace App\Models;

class Model 
{
    
    function __construct($container)
    {
        $this->container = $container;
    }
    
    protected function executeQuery($sql, $params = null) {
        if ($params == null)
        {
            $result = $this->container->get('db')->query($sql); // direct execution
        } else {
            $result = $this->container->get('db')->prepare($sql); // prepared execution
            $result->execute($params);
        }
        return $result;
    }
    
}