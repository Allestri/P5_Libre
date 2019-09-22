<?php 

namespace App\Controllers;

class AdminController extends Controller
{
    
    protected $container;
    
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    public function adminPanel($request, $response){
        
        $adminModel = $this->container->get('adminModel');
        
        $args = $adminModel->countReports();
        // If there are any reports, fetch those ones.
        if($args > 1){
            
            $args['reportedImgs'] = $adminModel->getReports();
            
        }
          
        return $this->render($response, 'pages/admin.twig', $args);
    }
    
    
}