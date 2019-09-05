<?php 


namespace App\Controllers;

class MembersController extends Controller 
{
    
    protected $container;
    
    function __construct($container)
    {
        $this->container = $container;
    }
    
    
    public function getFormInscripton($request, $response)
    {
        return $this->container->view->render($response, 'pages/inscription.twig');
    }
    
    // Inscription
    public function postSignup($request, $response)
    {
        $membersModel = $this->container->get('membersModel');
        $userEntries = $request->getParsedBody();       
        
        $username = $userEntries['uid'];
        $password = $userEntries['pwd'];
        $passwordRpt = $userEntries['pwdRpt'];
        
        $ip = $_SERVER['REMOTE_ADDR'];
        
        if(isset ($username))
        {
            $isUnique = $membersModel->checkMemberUnique($username);
            // Verification pseudo disponible
            //if($sql->rowCount() > 0)
            //if(($sql) > 0)
            
            if(!$isUnique)
            {
                echo 'Ce pseudo n\'est pas disponible';
            }
            elseif ($password == $passwordRpt){
                $rdp = password_hash($password, PASSWORD_DEFAULT);
                //var_dump($rdp);
                $membersModel->signup($username, $rdp, $ip);
                echo 'Bienvenue';
            } else {
                echo 'Mauvaise combinaison de mdp';
            }
        }
        
        return $this->container->view->render($response, 'pages/inscription.twig');
    }
    
    
    public function login($request, $response)
    {
        $userEntries = $request->getParsedBody();

        $username = $userEntries['uname'];
        
        $membersModel = $this->container->get('membersModel');

        $connexion = false;
        $member = $membersModel->getAccountInfo($username);
        
        if(isset($username) && ($userEntries['pwd'])){
            
            $isPwdCorrect = password_verify($userEntries['pwd'], $member['password']);
            
            if($isPwdCorrect){
                $_SESSION['username'] = $username;
                $_SESSION['uid'] = $member['id'];
                $connexion = true;
            } else {
                $connexion = false;
            }
        }
        if($connexion){
           $this->displayProfile($request, $response, $member);           
        } else {
            echo 'il y a une erreur';
        }
    }
    
    
    public function countImgMember($userId)
    {
        $imageModel = $this->container->get('imagesModel');        
        $imgNumber = $imageModel->countImgMember($userId);
        
        return $imgNumber;
    }
        
    
    public function displayProfile($request, $response, $member)
    {
        
        $memberModel = $this->container->get('membersModel');  
        
        $username = $_SESSION['username'];
        $uid = $_SESSION['uid'];
        $member['profile'] = $username;
        $member['uid'] = $uid;        
        
        if(isset($_SESSION['username'])){
            
            // Gets images number this user uploaded.
            $imgNbr = $this->countImgMember($uid);
            $member = array_merge($member, $imgNbr);
            
            // Friend Request notifications
            $friendReqs['datas'] = $memberModel->getFriendRequests($uid);
            //var_dump($friendReqs);
            
            
            return $this->container->view->render($response, 'pages/account.twig', $friendReqs);
        } else {
            return $this->redirect($response, 'home');
        }
        
    }
    
    
    
    public function logout($request, $response)
    {
        session_unset();
        // Troubleshooting Session flash
        session_destroy();
        return $this->redirect($response, 'home');
    }
    
    public function displayMembersList($request, $response)
    {
        
        $memberModel = $this->container->get('membersModel');
        $username = $_SESSION['username'];
        $membersList['datas'] = $memberModel->getMembersList($username);
        
        
        return $this->container->view->render($response, 'pages/members.twig', $membersList);
    }
    
    // Friendship System
    
    public function getIds($uid, $fid)
    {
        $ids = array("userId" => $uid, "friendId" => $fid);
        $this->setIds($ids);
    }
    
    public function addFriendRequest($request, $response)
    {
        
        $datas = $request->getParsedBody();

        $myId = $datas['myId'];
        $memberId = $datas['memberId'];
        $_SESSION['fid'] = $memberId;
        
        $memberModel = $this->container->get('membersModel');
        $memberModel->addFriendRequest($myId, $memberId);
        //$this->getIds($myId, $memberId);
        
        return $this->container->view->render($response, 'pages/members.twig');
    }
       
    public function ignoreFriendRequest()
    {
        $membersModel = $this->container->get('membersModel');
        
        $uid = $_SESSION['uid'];
        $fid = $_SESSION['fid'];         
        
        $membersModel->clearFriendRequest($uid, $fid);
    }
    
    public function acceptFriend()
    {
        $memberModel = $this->container->get('membersModel');
        
        $uid = $_SESSION['uid'];
        $fid = $_SESSION['fid'];  
        
        $memberModel->addFriendAccept($uid, $fid);
        $memberModel->clearFriendRequest($uid, $fid);
        
    }
    
    
    
}