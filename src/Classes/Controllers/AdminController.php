<?php 

namespace App\Controllers;

use Exception;

class AdminController extends Controller
{
    
    protected $container;
    
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    public function adminPanel($request, $response)
    {
        
        if(!isset($_SESSION['admin'])) 
            {
                throw new Exception('L\'accès de cette partie du site est restreint.');
            }
            
            try 
            {
                $adminModel = $this->container->get('adminModel');
                $membersModel = $this->container->get('membersModel');
                $directory = $this->container->get('uploaded_directory');
                
                // Stats
                $likesNbr = $adminModel->getLikesNbr();
                $commentsNbr = $adminModel->getCommentsNbr();
                $args = array_merge($likesNbr, $commentsNbr);
    
                // Photos total number
                $photosDir  = $directory . DIRECTORY_SEPARATOR . "photos" . DIRECTORY_SEPARATOR;
                $photosNbr = glob($photosDir . '*');
    
                $args['totalPhoto'] = count($photosNbr);
                
                
                
                // Reports
                $reportsNbr = $adminModel->countReports();
                $args = array_merge($args, $reportsNbr);
                // If there are any reports, fetch those ones.
                if($args['reportsNbr'] > 1){
                    
                    $args['reportedPosts'] = $adminModel->getReports();
                    
                }
                          
                // Quarantine files
                $quarantineDir = $directory . DIRECTORY_SEPARATOR . "quarantine" . DIRECTORY_SEPARATOR;
                $quarFiles = glob($quarantineDir . '*');
                if($quarFiles != false)
                {
                    $args['fileQuarantine'] = count($quarFiles);
                }
                
                // Memberlist
                $totalMembers = $membersModel->countAllMembers();
                $totalMembers = (int)$totalMembers['totalmembers'];
    
                $limit = 4;
                $args['pagination'] = $this->pagination($request, $totalMembers, $limit);
                $args['membersList'] = $membersModel->getAllMembersLimit($limit, $args['pagination']['offset']);
                
                
                // Logs
                $args['logs'] = $adminModel->getLogs();
                var_dump($args);
    
                return $this->render($response, 'pages/admin.twig', $args);
            }
            
            catch (Exception $e)
            {
                echo $e->getMessage();
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