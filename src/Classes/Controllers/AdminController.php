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
        
        if(isset($_SESSION['admin'])){
            
            $adminModel = $this->container->get('adminModel');
            var_dump($_SESSION);
            $args = $adminModel->countReports();
            // If there are any reports, fetch those ones.
            if($args > 1){
                
                $args['reportedImgs'] = $adminModel->getReports();
                
            }
            return $this->render($response, 'pages/admin.twig', $args);
            
        } else {
            echo 'Accès restreint';
        }
        
    }
    
    public function getSelectedPost($request, $response)
    {
        $datas = $request->getParsedBody();
        $imgId = $datas['imgId'];
        
        $adminModel = $this->container->get('adminModel');
        $report = $adminModel->getSelectedReport($imgId);
        
        echo json_encode($report);
    }
    
    public function editPost($request, $response)
    {
        $datas = $request->getParsedBody();
        $imgId = $datas['imgId'];
        
        $adminModel = $this->container->get('adminModel');
        $adminModel->editPost($imgId);
        
    }
    
    public function deletePost($request, $response)
    {
        $datas = $request->getParsedBody();
        $imgId = $datas['imgId'];
        
        $adminModel = $this->container->get('adminModel');
        $adminModel->deleteImage($imgId);
        
    }
    
}