<?php 


namespace App\Controllers;

use Exception;

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
    
    public function getFormLogin($request, $response)
    {
        return $this->container->view->render($response, 'pages/debug-login.twig');
    }
    
    // Inscription
    public function postSignup($request, $response)
    {
        $membersModel = $this->container->get('membersModel');
        $userEntries = $request->getParsedBody();       
        
        $username = $userEntries['uid'];
        $password = $userEntries['pwd'];
        $passwordRpt = $userEntries['pwdRpt'];
        
        //$ip = $_SERVER['REMOTE_ADDR'];
        
        if(isset ($username))
        {
            // If Username available.
            $isUnique = $membersModel->checkMemberUnique($username);
            
            if(!$isUnique)
            {
                $this->flash('Ce pseudonyme n\'est pas disponible', 'warning');
                return $this->redirect($response, 'inscription');
            }
            elseif ($password == $passwordRpt){
                $rdp = password_hash($password, PASSWORD_DEFAULT);
                $membersModel->signup($username, $rdp);
                // Avatar(personal) folder creation
                $directory = $this->container->get('uploaded_directory');
                mkdir($directory . DIRECTORY_SEPARATOR . "avatar" . DIRECTORY_SEPARATOR . $username);
                $this->flash('Inscription effectuée ! Vous pouvez dès à présent vous connecter', 'success', 'important');
                return $this->redirect($response, 'home');
            } else {
                $this->flash('Mauvaise combinaison de mot de passe', 'warning');
                return $this->redirect($response, 'inscription');
            }
        }
        
        
    }
    
    
    public function login($request, $response)
    {
        $userEntries = $request->getParsedBody();

        $username = $userEntries['uname'];
        
        $membersModel = $this->container->get('membersModel');

        $connexion = false;
        $member = $membersModel->getAccountInfo($username);
        
        if(empty($member)) {
            return $this->flashAjax('Ce pseudonyme n\'existe pas !', 'error', 'username');
        }
        
        if(isset($username) && ($userEntries['pwd'])){
            
            $isPwdCorrect = password_verify($userEntries['pwd'], $member['password']);
            
            if($isPwdCorrect){
                
                $_SESSION['username'] = $username;
                $_SESSION['uid'] = $member['id'];
                // Checks if avatar set on db
                $_SESSION['avatar'] = isset($member['avatar_file']) ? $member['avatar_file'] : 'no_avatar';
                
                if($member['group_id'] == 1){
                    $_SESSION['admin'] = $member['group_id'];
                }
                $connexion = true;
            } else {
                $connexion = false;
            }
        }
        
        if(!$connexion)
        {
            return $this->flashAjax('La connexion a echouée', 'error', 'failed');
        }
           
           
        return $this->flashAjax('Connexion effectuee');
           
       //$this->container->flash->addMessage('Test', 'This is a message');
       //return $this->redirect($response, 'profile');
         
        
    }
    
    
    public function loginDebug($request, $response)
    {
        $userEntries = $request->getParsedBody();
        
        $username = $userEntries['uname'];
        
        $membersModel = $this->container->get('membersModel');
        
        $connexion = false;
        $member = $membersModel->getAccountInfo($username);
        
        if(isset($username) && ($userEntries['pwd'])){
            
            $isPwdCorrect = password_verify($userEntries['pwd'], $member['password']);
            $this->flash('Erreur de connexion', 'error');
            if($isPwdCorrect){
                
                $_SESSION['username'] = $username;
                $_SESSION['uid'] = $member['id'];
                
                
                // Checks if avatar set on db
                $_SESSION['avatar'] = isset($member['avatar_file']) ? $member['avatar_file'] : 'no_avatar';
                
                /*
                if(isset($member['avatar_file'])){
                    $_SESSION['avatar'] = $member['avatar_file'];
                } else {
                    $_SESSION['avatar'] = 'no_avatar';
                }
                */                
                
                if($member['group_id'] == 1){
                    $_SESSION['admin'] = $member['group_id'];
                }
                $connexion = true;
            } else {
                $connexion = false;
            }
        }
        
        if(!$connexion)
        {
            $this->flash('Erreur de connexion', 'error');
            return $this->redirect($response, 'home');
        }

        /*
        // Greet message
        $this->flash('Bienvenue sur votre profil');
        
        $this->container->flash->addMessage('Test', 'This is a message');
        return $this->redirect($response, 'profile');
        */
    }
    
    
    public function countImgMember($userId)
    {
        $imageModel = $this->container->get('imagesModel');        
        $imgNumber = $imageModel->countImgMember($userId);
        
        return $imgNumber;
    }
               
    
    public function displayProfile($request, $response, $member)
    {
        // Add message to be used in current request
        //$this->container->flash->addMessageNow('Test', 'This is another message');
        
        $memberModel = $this->container->get('membersModel');
        $contentModel = $this->container->get('contentModel');
        $imagesModel = $this->container->get('imagesModel');
        
        $username = $_SESSION['username'];
        $uid = $_SESSION['uid'];
        $member['profile'] = $username;
        $member['uid'] = $uid;
        
        //$timestamp = $contentModel->getDate();
        //$date = $this->relativeDate($timestamp);
        //var_dump($date);
        //var_dump($date);
        //var_dump($_SESSION);
        
        if(isset($_SESSION['username'])){
            
            
            // Gets images number this user uploaded.
            $imgNbr = $this->countImgMember($uid);
            $args = array_merge($member, $imgNbr);
            
            $hasAvatars = $memberModel->getAvatars($uid);
            //var_dump($avatars['0']);
            
            $avatarDir = "uploads/avatar";
            // Checks has custom avatar, gets it, return default avatar otherwise.
            // Gets inactive avatars in a separate array.
            if($hasAvatars){
                
                $avatar['avatar'] = $avatarDir . DIRECTORY_SEPARATOR . $username . DIRECTORY_SEPARATOR . $hasAvatars['0']['avatar_file'];
                $avatar['inactive_avatars'] = array_slice($hasAvatars,1);
                
            } else {
                $avatar['avatar'] = $avatarDir . DIRECTORY_SEPARATOR . "avatar_default.png";
            }
            $args = array_merge($args, $avatar);
            
            // Get friends
            $args['friends'] = $memberModel->getFriends($uid);
            
            $args['myimages'] = $imagesModel->fetchAllMyImgs($uid);
            
            // Friend Request notifications
            $args['request'] = $memberModel->getFriendRequests($uid);
            if($args['request']){
                $this->flash('Vous avez une nouvelle notification');
            }
            
            $args['commentslist'] = $contentModel->getMyComments($uid);
            $args['commentsNbr'] = count($args['commentslist']);
            
            /*
            if(isset($args['request']['0'])){
                $_SESSION['sender_id'] = $args['request']['0']['sender_id'];
            }
            */
            return $this->container->view->render($response, 'pages/profile.twig', $args);
        } else {
            return $this->redirect($response, 'home');
        }
        
    }
       
    
    public function logout($request, $response)
    {
        session_unset();
        // Troubleshooting Session flash
        session_destroy();
        
        $this->flash('Deconnecté');
        return $this->redirect($response, 'home');
    }
    
    public function displayMembersList($request, $response)
    {        
        
        if(isset ($_SESSION['uid'])) {
            
        
            $memberModel = $this->container->get('membersModel');
            $username = $_SESSION['username'];
            $userId = $_SESSION['uid'];              
            
            
            $totalMembers = $memberModel->countAllMembers();
            $totalMembers = (int)$totalMembers['totalmembers'];
          
            //var_dump($totalMembers);
            
            $limit = 4;
            // To do : Total member -1 ( actual connected member )
            
            $args = $this->pagination($request, $totalMembers, $limit);
                  
            $args['memberslist'] = $memberModel->getAllMembersLimit($limit, $args['offset'], $userId);
            
            $relationships = $memberModel->getFriendships($userId);
            
            
            // Putting relationship status on memberlist array
            foreach($args['memberslist'] as $key1=>$value1)
            {
                foreach($relationships as $key2=>$value2)
                {
                    // Determining the actual direction of a given request :
                    // true : from user to others / false = from others to user.
                    $direction = $value2['friend_a'] == $userId;
    
                    if($direction){
                        $fid = $value2['friend_b'];
                    } else {
                        $fid = $value2['friend_a'];
                    }
                    
                    
                    if($value1['id'] == $fid)
                    {
                        // Out = Outgoing friend request from user to others
                        // In = Incoming friend request from others
                        if($value2['status'] == "pending")
                        {
                            $args['memberslist'][$key1]['req_direction'] = $direction ? "out" : "in" ;
                            if($value2['ignored'] == 1){
                                $args['memberslist'][$key1]['ignored'] = true;
                            }
                        }
                        $args['memberslist'][$key1]['status'] = $value2['status'];
                    }
                        
                    
                }
                
            }
            //unset($users); // unset the reference
                    
            
            return $this->container->view->render($response, 'pages/members.twig', $args);
        } else {
            return $this->redirect($response, 'home');
        }
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
        
        $memberModel = $this->container->get('membersModel');
        $memberModel->addFriendRequest($myId, $memberId);
        
        $this->flash('Votre demande d\'ami a bien été envoyée');
        
        return $this->redirect($response, 'memberList');
    }
    
    public function cancelFriendRequest($request, $response)
    {
        $datas = $request->getParsedBody();

        $uid = $datas['myId'];
        $fid = $datas['memberId'];
        
        $memberModel = $this->container->get('membersModel');
        $memberModel->cancelFriendRequest($uid, $fid);
        
        $this->flash('Goodbye my old friend');
        
        return $this->redirect($response, 'memberList');
    }
       
    public function ignoreFriendRequest($request, $response)
    {
        $membersModel = $this->container->get('membersModel');
        $datas = $request->getQueryParams();
        
        $uid = $_SESSION['uid'];
        $fid = $datas['fid'];
        
        $membersModel->ignoreFriendRequest($fid, $uid);
        $this->flash('Demande d\'ami ignorée');
        
        return $this->redirect($response, 'profile');
    }
    
    public function acceptFriend($request, $response)
    {
        $memberModel = $this->container->get('membersModel');
        $datas = $request->getQueryParams();
        
        $uid = $_SESSION['uid'];
        $fid = $datas['fid'];
                
        $memberModel->addFriendAccept($fid, $uid);
        
        $this->flash('Hello Friend.');
        
        return $this->redirect($response, 'profile');
              
    }
    
    public function removeFriend($request, $response)
    {
        $memberModel = $this->container->get('membersModel');
        $datas = $request->getParsedBody();
        
        $uid = $_SESSION['uid'];
        $fid = $datas['memberId'];
        
        $memberModel->removeFriend($uid, $fid);
        
        $this->flash('Goodbye my old friend');
        
        return $this->redirect($response, 'memberList');
    }
    
    // in a special case anyone previously ignored a given request.
    public function reAddFriendRequest($request, $response)
    {
        
        $memberModel = $this->container->get('membersModel');
        $datas = $request->getParsedBody();
        
        $uid = $_SESSION['uid'];
        $fid = $datas['memberId'];
        
        $memberModel->reAddFriendRequest($uid, $fid);
        
        $this->flash('Demande d\'ami envoyée');
        
        return $this->redirect($response, 'memberList');
        
    }
    
    
    // Settings manager
    public function changeSettings($request, $response) 
    {
        $uid = $_SESSION['uid'];
        $member = $_SESSION['username'];
        $membersModel = $this->container->get('membersModel');
        
        $userEntries = $request->getParsedBody();
        $uploadedFile = $request->getUploadedFiles();
        $uploadedFile = $uploadedFile['myavatar'];
               
        // If user selected an unactive avatar.
        if(isset($userEntries['avatarId']) && ($userEntries['avatarId'] !== '1')){
            
            $avatarId = $userEntries['avatarId'];
            $this->switchAvatar($uid, $avatarId);
            
            $this->flash('Votre avatar a bien été mis à jour');
            return $this->redirect($response, 'profile');
        }
        
        if(!empty($uploadedFile) && ($uploadedFile->getError() === UPLOAD_ERR_OK)){
                                 
            $directory = $this->container->get('uploaded_directory');
            
            
            /* 
            $avatarDir = $directory . DIRECTORY_SEPARATOR . "avatar" . DIRECTORY_SEPARATOR . $member;
            Deletes the previous avatar if there is one
             $scan = scandir($avatarDir,1);
             var_dump($scan);
             if(isset($scan['0'])){
             unlink($avatarDir . DIRECTORY_SEPARATOR . $scan['0']);
             }
             */
            
            $this->addNewAvatar($directory, $uploadedFile, $member);
            
            // for debugging purposes
            //return $this->container->view->render($response, 'pages/account.twig');
            $this->flash('Votre avatar a bien été mis à jour');
            return $this->redirect($response, 'profile');
        }
        
        if(!empty($userEntries['password']) && ($userEntries['passwordRpt'])){
            
            $password = $userEntries['password'];
            $passwordRpt = $userEntries['passwordRpt'];
            var_dump($userEntries);
            
            if ($password == $passwordRpt){
                $pass = password_hash($password, PASSWORD_DEFAULT);
                $membersModel->changePassword($pass, $uid);
                $this->flash('Votre mot de passe a été correctement modifié');
                return $this->redirect($response, 'profile');
            } else {
                $this->flash('Les mots de passes doivent être identiques', 'error');
                return $this->redirect($response, 'profile');
            }
            
        }
        
        if(!empty($userEntries['email'])){
            
            $email = $userEntries['email'];
            $membersModel->setEmail($email, $uid);
            
            $this->flash('Votre email a bien été mise à jour');
            return $this->redirect($response, 'profile');
        }
        
        // If the user didn't change anything, returns a redirect + flash message.
        $this->flash('Vous n\'avez pas fait de modifications sur votre profil');
        return $this->redirect($response, 'profile');
        
    }
        
    // Avatars
    
    public function switchAvatar($uid, $avatarId)
    {
        $memberModel = $this->container->get('membersModel');
                
        $memberModel->unactiveAvatars($uid);
        $memberModel->switchAvatar($avatarId, $uid);
        
    }
    
    public function deleteAvatar($request, $response)
    {
        $datas = $request->getQueryParams();
        $avatarId = $datas['id'];
        $filename = $datas['filename'];
        
        $member = $_SESSION['username'];
        
        $memberModel = $this->container->get('membersModel');
        $directory = $this->container->get('uploaded_directory');
        $avatarDir = $directory . DIRECTORY_SEPARATOR . "avatar" . DIRECTORY_SEPARATOR . $member;
        
        $memberModel->deleteAvatar($avatarId);
        
        unlink($avatarDir . DIRECTORY_SEPARATOR . $filename);
        
        $this->flash('Votre profil a bien été mis à jour');
        return $this->redirect($response, 'profile');
    }
                
   
    function addNewAvatar($directory, $uploadedFile, $member)
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
        $memberModel->unactiveAvatars($uid);
        // Sends the filename to the DB,
        // Then sets has custom avatar.
        $memberModel->changeAvatarNew($uid, $filename);
        $memberModel->hasCustomAvatar();
        
        // Refreshes Session avatar
        $_SESSION['avatar'] = $filename;
        
        
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . "avatar" . DIRECTORY_SEPARATOR . $member . DIRECTORY_SEPARATOR . $filename);
        
        return $filename;
    }
    
    
    
}