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
        
        var_dump($passwordRpt);
        
        if(isset ($username))
        {
            $username = htmlspecialchars($username);
            //var_dump($username);
            $sql = $membersModel->getAccountInfo();
            // Verification pseudo disponible
            if($sql->rowCount() > 0)
            {
                echo 'Ce pseudo n\'est pas disponible';
            }
            elseif ($password == $passwordRpt){
                $membersModel->signup();
                echo 'Bienvenue';
            } else {
                echo 'Mauvaise combinaison de mdp';
            }
        }
        
        return $this->container->view->render($response, 'pages/inscription.twig');
    }
    
    public function logout($request, $response)
    {
        session_unset();
        session_destroy();
        return $this->redirect($response, 'accueil');
    }
    
    
    
}