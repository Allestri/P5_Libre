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
        $this->twig->addGlobal('member', isset($_SESSION['username']) ? $_SESSION['username'] : []);
        
        return $next($request, $response);
    }
}