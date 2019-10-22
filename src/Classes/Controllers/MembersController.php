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
            // If Username available.
            $isUnique = $membersModel->checkMemberUnique($username);
            
            if(!$isUnique)
            {
                echo 'Ce pseudo n\'est pas disponible';
            }
            elseif ($password == $passwordRpt){
                $rdp = password_hash($password, PASSWORD_DEFAULT);
                $membersModel->signup($username, $rdp, $ip);
                // Avatar(personal) folder creation
                $directory = $this->container->get('uploaded_directory');
                mkdir($directory . DIRECTORY_SEPARATOR . "avatar" . DIRECTORY_SEPARATOR . $username);
                echo 'Bienvenue';
            } else {
                echo 'Mauvaise combinaison de mot de passe';
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
                if($member['group_id'] == 1){
                    $_SESSION['admin'] = $member['group_id'];
                }
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
        
        //var_dump($_SESSION);
        
        if(isset($_SESSION['username'])){
            
                       
            // Gets images number this user uploaded.
            $imgNbr = $this->countImgMember($uid);
            $args = array_merge($member, $imgNbr);
            
            $hasAvatars = $memberModel->getAvatars($uid);
            //var_dump($avatars['0']);
            var_dump($hasAvatars);
            
            $avatarDir = "uploads/avatar";
            
            // Checks has custom avatar, gets it, return default avatar otherwise.
            // Gets inactive avatars in a separate array.
            if($hasAvatars){
                
                $avatar['avatar'] = $avatarDir . DIRECTORY_SEPARATOR . $username . DIRECTORY_SEPARATOR . $hasAvatars['0']['avatar_file'];
                $avatar['inactive_avatars'] = array_slice($hasAvatars,1);
                var_dump($avatar);
                
            } else {
                $avatar['avatar'] = $avatarDir . DIRECTORY_SEPARATOR . "avatar_default.png";
            }
            $args = array_merge($args, $avatar);
            
            // Get friends
            $args['friends'] = $memberModel->getFriends($uid);
            
            $args['recentimg'] = $memberModel->getRecentPhotos($uid);
            
            // Friend Request notifications
            $args['request'] = $memberModel->getFriendRequests($uid);
            var_dump($args);
            
            
            if(isset($args['request']['0'])){
                $_SESSION['sender_id'] = $args['request']['0']['sender_id'];
            }
            
            return $this->container->view->render($response, 'pages/account.twig', $args);
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
        $_SESSION['sender_id'] = $memberId;
        
        $memberModel = $this->container->get('membersModel');
        $memberModel->addFriendRequest($myId, $memberId);
        //$this->getIds($myId, $memberId);
        
        return $this->container->view->render($response, 'pages/members.twig');
    }
       
    public function ignoreFriendRequest($request, $response)
    {
        $membersModel = $this->container->get('membersModel');

        $uid = $_SESSION['uid'];
        $fid = $_SESSION['sender_id'];
        
        $membersModel->clearFriendRequest($fid, $uid);
        
        return $this->container->view->render($response, 'pages/account.twig');
    }
    
    public function acceptFriend($request, $response)
    {
        $memberModel = $this->container->get('membersModel');
        
        $uid = $_SESSION['uid'];
        $fid = $_SESSION['sender_id'];
                
        $memberModel->addFriendAccept($fid, $uid);
        $memberModel->clearFriendRequest($fid, $uid);
        
        return $this->container->view->render($response, 'pages/account.twig');
              
    }
    
    
    
    public function switchAvatar($request, $response)
    {
        $uid = $_SESSION['uid'];
        $memberModel = $this->container->get('membersModel');
        
        $datas = $request->getParsedBody();
        $avatarId = $datas['avatarId'];
        
        $memberModel->unactiveAvatars($uid);
        $memberModel->switchAvatar($avatarId, $uid);
        
        return $this->redirect($response, 'profile');
    }
    // Upload Avatar
    
    public function addNewAvatar($request, $response)
    {
                
        $uploadedFile = $request->getUploadedFiles();
        $directory = $this->container->get('uploaded_directory');
        
        $uploadedFile = $uploadedFile['image'];

        $member = $_SESSION['username'];
                
        $avatarDir = $directory . DIRECTORY_SEPARATOR . "avatar" . DIRECTORY_SEPARATOR . $member;
        
        /* Deletes the previous avatar if there is one 
        $scan = scandir($avatarDir,1);
        var_dump($scan);
        if(isset($scan['0'])){
            unlink($avatarDir . DIRECTORY_SEPARATOR . $scan['0']);
        }
        */

        $this->moveUploadedAvatar($directory, $uploadedFile, $member);
        
        // for debugging purposes
        //return $this->container->view->render($response, 'pages/account.twig');
        return $this->redirect($response, 'profile');
    }
    
    
    
    function moveUpLoadedAvatar($directory, $uploadedFile, $member)
    {
        
        $memberModel = $this->container->get('membersModel');
        $uid = $_SESSION['uid'];
        
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        
        $basename = "avatar";
        $author = strtolower($_SESSION['username']);
        // Makes the filename unique
        $fid = date('H-i-s');
        $filename = $basename ."_". $author ."_". $fid . "." . $extension;
        
        // Unactive previous avatar on DB
        $memberModel->unactiveAvatar($uid);
        // Sends the filename to the DB,
        // Then sets has custom avatar.
        $memberModel->changeAvatarNew($uid, $filename);
        $memberModel->hasCustomAvatar();
        
        
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . "avatar" . DIRECTORY_SEPARATOR . $member . DIRECTORY_SEPARATOR . $filename);
        
        return $filename;
    }
    
    
    
}