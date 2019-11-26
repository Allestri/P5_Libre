<?php

namespace App\Models;

use PDO;

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
    
    protected function executeLimitQuery($sql, $limit, $offset, $params = null) {
        if ($params = null)
        {
            $result = $this->container->get('db')->query($sql);
        } else {
            $result = $this->container->get('db')->prepare($sql);
            $result->bindValue(':limit', $limit, PDO::PARAM_INT);
            $result->bindValue(':offset', $offset, $this->container->get('db')::PARAM_INT);
            $result->execute($params);
        }
        return $result;
    }
       
}