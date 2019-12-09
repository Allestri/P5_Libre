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
            $membersModel = $this->container->get('membersModel');
            
            $args = $adminModel->countReports();
            
            // If there are any reports, fetch those ones.
            if($args > 1){
                
                $args['reportedPosts'] = $adminModel->getReports();
                
            }
            
            
            // Count quarantine files
            $directory = $this->container->get('uploaded_directory');
            $quarantineDir = $directory . DIRECTORY_SEPARATOR . "quarantine" . DIRECTORY_SEPARATOR;
            $files = glob($quarantineDir . '*');
            var_dump($quarantineDir);
            if($files != false)
            {
                $args['fileQuarantine'] = count($files);
            }
            
            // Fetch memberlist
            $args['membersList'] = $membersModel->getFullMembersList();
            // Logs
            $args['logs'] = $adminModel->getLogs();
            var_dump($args);
            return $this->render($response, 'pages/admin.twig', $args);
            
        } else {
            echo 'Accès restreint';
        }
        
    }
    
    public function clearReports($request, $response)
    {
        
        $adminModel = $this->container->get('adminModel');
        $adminModel->clearAllReports();
        
        return $this->redirect($response, 'admin');
    }
    
    public function clearQuarantineDir($request, $response)
    {
        $directory = $this->container->get('uploaded_directory');
        $quarantineDir = $directory . DIRECTORY_SEPARATOR . "quarantine" . DIRECTORY_SEPARATOR;
        
        array_map('unlink', glob($quarantineDir . '*'));
        
        $this->flash('La quarantaine a été vidée de ses photos');
        return $this->redirect($response, 'admin');
    }
    
    public function getSelectedPost($request, $response)
    {
        $datas = $request->getParsedBody();
        $postId = $datas['postId'];
        
        $adminModel = $this->container->get('adminModel');
        $report = $adminModel->getSelectedReport($postId);
        
        echo json_encode($report);
    }
    
    public function editPost($request, $response)
    {
        $datas = $request->getParsedBody();
        $postId = $datas['postId'];
        $name = $datas['name'];
        $content = $datas['description'];
        
        $adminModel = $this->container->get('adminModel');
        $adminModel->editPost($name, $content, $postId);
        
        return $this->redirect($response, 'admin');
        
    }
    
    public function deletePost($request, $response)
    {
        $datas = $request->getParsedBody();
        $postId = $datas['postId'];
        
        $adminModel = $this->container->get('adminModel');
        $adminModel->deletePost($postId);
        
    }
    
}