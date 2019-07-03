<?php
// Saves old values on form if errors occured.

namespace App\Middlewares;


class OldValuesMiddleware

{
    
    protected $twig;
    
    public function __construct($twig)
    {
        $this->twig = $twig;
    }
    
    public function __invoke($request, $response, $next){
        
        $this->twig->addGlobal('old', isset($_SESSION['old']) ? $_SESSION['old'] : []);
        
        if(isset($_SESSION['old'])){
            unset($_SESSION['old']);
        }
        $response = $next($request, $response);
        if($response->getStatusCode() != 200){
            $_SESSION['old'] = $request->getParams();
        }
        return $response;
    }
    
}