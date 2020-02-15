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
                //var_dump($args['reportsNbr']);
                if($args['reportsNbr'] >= 1){
                    
                    $args['reportedPosts'] = $adminModel->getReports();
                    
                }
                
                $args['reportedComments'] = $adminModel->getReportsComments();
                          
                // Quarantine files
                $quarantineDir = $directory . DIRECTORY_SEPARATOR . "quarantine" . DIRECTORY_SEPARATOR;
                $quarFiles = glob($quarantineDir . '*');
                if($quarFiles != false)
                {
                    $args['fileQuarantine'] = count($quarFiles);
                }
                
                // Memberlist
                $totalMembers = $membersModel->countAllMembers();
                // Making sure to get an int from it.
                $totalMembers = (int)$totalMembers['totalmembers'];
                // Variable for template.
                $args['membersNbr'] = $totalMembers;
                   
                $limit = 4;
                $args['pagination'] = $this->pagination($request, $totalMembers, $limit);
                $args['membersList'] = $adminModel->getMembers($limit, $args['pagination']['offset']);
                                
                // Logs
                $args['logs'] = $adminModel->getLogs();
                $args['logs_com'] = $adminModel->getComLogs();
                
                return $this->render($response, 'pages/admin.twig', $args);
            }
            
            catch (Exception $e)
            {
                echo $e->getMessage();
            }

    }
    
    public function paginateMembers($request, $response)
    {
        $limit = 4;
        $membersModel = $this->container->get('membersModel');
        $totalMembers = $membersModel->countAllMembers();
        
        $adminModel = $this->container->get('adminModel');
        $totalMembers = (int)$totalMembers['totalmembers'];
        
        $args['pagination'] = $this->pagination($request, $totalMembers, $limit);
        $args['membersList'] = $adminModel->getMembers($limit, $args['pagination']['offset']);
        
        return $this->render($response, 'pages/admin.twig', $args);       
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
    
    // Reports
    
    public function clearPostReport($request, $response)
    {
        $datas = $request->getParsedBody();
        $postId = $datas['postId'];
        
        $adminModel = $this->container->get('adminModel');
        $adminModel->clearReport($postId);
        
        $this->flash('Le signalement a été supprimé avec succès');
        return $this->redirect($response, 'admin');
    }
    
    public function clearReports($request, $response)
    {
        
        $adminModel = $this->container->get('adminModel');
        $adminModel->clearAllReports();
        
        $this->flash('Tous les signalements de posts ont étés ignorés');
        return $this->redirect($response, 'admin');
    }
    
    public function clearCommentReport($request, $response)
    {
        
        $datas = $request->getQueryParams();
        $commentId = $datas['commentId'];
        
        $adminModel = $this->container->get('adminModel');
        $adminModel->clearCommentReport($commentId);
        
        $this->flash('Ce signalement a été ignoré');
        return $this->redirect($response, 'admin');
    }
    
    public function clearAllCommentsReports($request, $response)
    {
        $adminModel = $this->container->get('adminModel');
        $adminModel->clearAllCommentsReports();
        
        $this->flash('Tous les signalements de commentaires ont étés ignorés');
        return $this->redirect($response, 'admin');
    }
    
    
    // Moderation
    
    public function editPost($request, $response)
    {
        $datas = $request->getParsedBody();
        $postId = $datas['postId'];
        $name = $datas['name'];
        $content = $datas['description'];
        
        // M as moderated
        $modType = 'M';
        
        $adminModel = $this->container->get('adminModel');
        $adminModel->insertPostLogs($modType, $postId);
        $adminModel->editPost($name, $content, $postId);
        $adminModel->clearReport($postId);
        
        $this->flash('Post edité avec succès');
        return $this->redirect($response, 'admin');
        
    }
    
    public function deletePost($request, $response)
    {
        $datas = $request->getParsedBody();
        $postId = $datas['postId'];
        
        $adminModel = $this->container->get('adminModel');
        $adminModel->deletePost($postId);
        $adminModel->clearReport($postId);
        
        $this->flash('Post supprimé avec succès');
        return $this->redirect($response, 'admin');
    }
    
    public function editComment($request, $response)
    {
        $datas = $request->getParsedBody();
        $commentId = $datas['commentId'];
        $content = $datas['content'];
        
        // M as moderated 
        $modType = 'M';
        
        $adminModel = $this->container->get('adminModel');
        $adminModel->insertComLogs($modType, $commentId);
        $adminModel->editComment($content, $commentId);
        
        $this->flash('Commentaire moderé avec succès');
        return $this->redirect($response, 'admin');
    }
    
    public function deleteComment($request, $response)
    {
        $datas = $request->getParsedBody();
        $commentId = $datas['commentId'];
        
        // D as deleted
        $modType = 'D';
        
        $adminModel = $this->container->get('adminModel');
        $adminModel->insertComLogs($modType, $commentId);
        $adminModel->deleteComment($commentId);
        
        $this->flash('Commentaire supprimé avec succès');
        return $this->redirect($response, 'admin');
    }
    
    
}