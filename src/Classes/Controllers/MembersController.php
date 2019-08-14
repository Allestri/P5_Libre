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
        
        $username = $_POST['uid'];
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
        $datas = $request->getParsedBody();

        $uid = $datas['uid'];
        var_dump($uid);
        
        $membersModel = $this->container->get('membersModel');
        $username = $_POST['uid'];
        $connexion = false;
        $member = $membersModel->getAccountInfo($username);
        
        if(isset($username) && ($_POST['pwd'])){
            
            $isPwdCorrect = password_verify($_POST['pwd'], $member['password']);
            
            if($isPwdCorrect){
                $_SESSION['uid'] = $username;
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
    
    public function displayProfile($request, $response, $member)
    {
        $username = $_SESSION['uid'];
        $member['profile'] = $username;
        var_dump($member);
        if(isset($_SESSION['uid'])){
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
    
    
    
}