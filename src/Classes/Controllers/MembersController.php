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
        
        $username = $_POST['username'];
        $password = $_POST['pwd'];
        $passwordRpt = $_POST['pwdRpt'];
        
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
        
        $username = $_SESSION['username'];
        $uid = $_SESSION['uid'];
        $member['profile'] = $username;
        $member['uid'] = $uid;
        
        // Gets images number this user uploaded.
        $imgNbr = $this->countImgMember($uid);
        $member = array_merge($member, $imgNbr);
        
        if(isset($_SESSION['username'])){
            return $this->container->view->render($response, 'pages/account.twig', $member);
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
        
        $membersList['datas'] = $memberModel->getMembersList();
        
        
        return $this->container->view->render($response, 'pages/members.twig', $membersList);
    }
    
    public function addFriend($request, $response)
    {
        
        $datas = $request->getParsedBody();
        var_dump($datas);
        $myId = $datas['myId'];
        $memberId = $datas['memberId'];
        
        $memberModel = $this->container->get('membersModel');
        $memberModel->addFriend($myId, $memberId);
        
        return $this->redirect($response, 'home');
    }
    
    
    
}