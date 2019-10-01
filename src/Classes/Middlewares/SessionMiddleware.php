<?php

namespace App\Middlewares;

class SessionMiddleware
{
    
    protected $twig;
    
    public function __construct($twig)
    {
        $this->twig = $twig;
    }
    
    public function __invoke($request, $response, $next)
    {
        $this->twig->addGlobal('member', isset($_SESSION['uid']) ? $_SESSION['uid'] : []);
        $this->twig->addGlobal('admin', isset($_SESSION['admin']) ? $_SESSION['admin'] : []);
        
        return $next($request, $response);
    }
}