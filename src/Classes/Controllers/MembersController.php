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
        
        if(isset ($username))
        {
            $username = htmlspecialchars($username);
            //var_dump($username);
            $sql = $membersModel->countMembers();
            var_dump($sql);
            // Verification pseudo disponible
            if($sql->rowCount() > 0)
            {
                echo 'Ce pseudo n\'est pas disponible';
            }
            elseif ($password == $passwordRpt){
                $rdp = password_hash($password, PASSWORD_DEFAULT);
                //var_dump($rdp);
                $membersModel->signup($rdp);
                echo 'Bienvenue';
            } else {
                echo 'Mauvaise combinaison de mdp';
            }
        }
        
        return $this->container->view->render($response, 'pages/inscription.twig');
    }
    
    
    public function login($request, $response)
    {
        $membersModel = $this->container->get('membersModel');
        $username = $_POST['uid'];
        $connexion = false;
        
        if(isset($username) && ($_POST['pwd'])){
            $member = $membersModel->getAccountInfo();
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
    
    public function displayProfile($request, $response, $members)
    {
        $args['member'] = $members;
        return $this->container->view->render($response, 'pages/account.twig', $args);
    }
    
    
    
    public function logout($request, $response)
    {
        session_unset();
        session_destroy();
        return $this->redirect($response, 'home');
    }
    
    
    
}