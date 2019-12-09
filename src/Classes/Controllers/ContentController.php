<?php

namespace App\Controllers;

use Respect\Validation\Validator;
use Slim\Http\UploadedFile;

class ContentController extends Controller
{
    protected $container;
    
    // Passes the DIC to get the model.
    function __construct($container)
    {
        $this->container = $container;
    }
    
    /* function __invoke($request, $response, $args)
    {
        
        $datas = $this->container->get('contentModel');
        
        $args['content'] = $datas->getContent();
        
        // get the template renderer and pass response and datas to the template file.
        return $this->container->get('renderer')->render($response, 'content.php', $args); 
    } */
        
    
    public function home($request, $response)
    {
        
        $imageModel = $this->container->get('imagesModel');
        $args['images'] = $imageModel->mostLikedImgs();
        
        return $this->render($response, 'home.twig', $args);
    }    
    
    public function getContent($request, $response, $args)
    {
        $this->flash('Test message flash');
        $contentModel = $this->container->get('contentModel');
        
        // Pagination
        $limit = 2;
        
        $contentTotal = $contentModel->countContent();
        $contentTotal = (int)$contentTotal['rows'];
        
        var_dump($contentTotal);

        $args = $this->pagination($request, $contentTotal, $limit);
        //$args['datas'] = $contentModel->getContent($limit, $args['offset']);
        $args['datas'] = $contentModel->getContent($limit, $args['offset']);
        var_dump($args);
        
        // get the template renderer and pass response and datas to the template file.
        return $this->container->view->render($response, 'content.twig', $args);
    }
    
     
     // Formulaire
     public function getForm($request, $response)
     {
         //$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : [];
         //$_SESSION['flash'] = [];
         return $this->render($response, 'pages/upload.twig');
     }
     
     public function getAddForm($request, $response)
     {
         return $this->container->view->render($response, 'pages/addForm.twig');
     }
     
     public function testFlash($request, $response)
     {
         $this->flash('Test message flash');
         
         return $this->render($response, 'content.twig');
     }
     
     
     // Retrieve post unique Id with it's unique filename.
     public function retrievePostId($request, $response)
     {
         $datas = $request->getParsedBody();
         
         // debugging purposes
         //$filename = $_POST['filename'];
         //$filename = '291b42e7a2b5b405.JPG';
         
         $filename = $datas['filename'];
         
         $contentModel = $this->container->get('contentModel');
         $postId = $contentModel->getPostId($filename);
         
         // Makes sure to convert value into integer.
         $idNbr = (int)$postId['id'];
         
         echo json_encode($idNbr);
     }
     
     // Retrieve post & image unique Ids with unique filename.
     public function retrieveIds($request, $response)
     {
         $datas = $request->getParsedBody();
         
         // debugging purposes
         //$filename = $_POST['filename'];
         //$filename = '291b42e7a2b5b405.JPG';
         
         $filename = $datas['filename'];
         
         $contentModel = $this->container->get('contentModel');
         $ids = $contentModel->getIds($filename);
         
         // Makes sure to convert value into integer.
         //$postId = (int)$ids['post_id'];
         //$imgId = (int)$ids['image_id'];
         
         echo json_encode($ids);
     }
     
     /* Social */
     
     public function getComments($request, $response)
     {
         $datas = $request->getQueryParams();
         $postId = $datas['postId'];
         
         $contentModel = $this->container->get('contentModel');
         $comments = $contentModel->getComments($postId);
         
         echo json_encode($comments);
     }
     
     public function getLikes($request, $response)
     {
         $datas = $request->getQueryParams();
         $postId = $datas['postId'];
         
         $contentModel = $this->container->get('contentModel');
         $likesNbr = $contentModel->getLikesNew($postId);
         
         echo json_encode($likesNbr);
         
     }
     
     public function commentPost($request, $response)
     {
         $datas = $request->getParsedBody();
         $uid = $datas['uid'];
         
         $content = $datas['content'];
         $postId = $datas['postId'];
         
         
         $contentModel = $this->container->get('contentModel');
         $contentModel->addComment($uid, $content, $postId);
     }
     
     public function deleteComment($request, $response)
     {
         $datas = $request->getParsedBody();
         $uid = $datas['uid'];
         $commentId = $datas['commentId'];
         
         $contentModel = $this->container->get('contentModel');
         $contentModel->deleteComment($uid, $commentId);
     }
     
     public function reportPost($request, $response)
     {
         $datas = $request->getParsedBody();
         $postId = $datas['postId'];
         $userId = $datas['userId'];
         
         $contentModel = $this->container->get('contentModel');
         $contentModel->reportPost($userId, $postId);
     }
     
     public function likePost($request, $response)
     {
         $datas = $request->getParsedBody();
         $postId = $datas['postId'];
         $userId = $datas['userId'];
                 
         $contentModel = $this->container->get('contentModel');
         $contentModel->likePostNew($userId, $postId);
     }
     
     public function unlikePost($request, $response)
     {
         $datas = $request->getParsedBody();
         $postId = $datas['postId'];
         $userId = $datas['userId'];
         
         $contentModel = $this->container->get('contentModel');
         $contentModel->unlikePost($userId, $postId);
     }
     
     // CRUD
     
     public function deletePost($request, $response)
     {
         // Mieux vaut en faire trop que pas assez.
         if(isset($_SESSION['uid'])) {
             
             $uid = $_SESSION['uid'];
             
             $datas = $request->getParsedBody();
             $imgId = $datas['imgId'];
             $postId = $datas['postId'];
             $filename = $datas['filename'];
             
             $imageModel = $this->container->get('imagesModel');
             $imageModel->deleteImage($imgId, $uid);
             
             $contentModel = $this->container->get('contentModel');
             $contentModel->deletePost($postId, $uid);
             
             $directory = $this->container->get('uploaded_directory');
             var_dump($filename);
             
             $photoPath = $directory . DIRECTORY_SEPARATOR . "photos" . DIRECTORY_SEPARATOR . $filename;
             $thumbPath = $directory . DIRECTORY_SEPARATOR . "thumbnails" . DIRECTORY_SEPARATOR . $filename;
             
             unlink($thumbPath);
             unlink($photoPath);
             
             $this->flash('Post supprimé avec succès');
             
         } else {
             
             echo 'Erreur, vous devez vous authentifier';
         }
             
     }
     
     public function getSelectedPost($request, $response)
     {
         $datas = $request->getParsedBody();
         $postId = $datas['postId'];
         
         $contentModel = $this->container->get('contentModel');
         $post = $contentModel->getSelectedPost($postId);
         
         echo json_encode($post);
     }
     
     
     public function editPost($request, $response)
     {
         $datas = $request->getParsedBody();
         $contentModel = $this->container->get('contentModel');
         
         $postId = $datas['postId'];
         $name = $datas['name'];
         $content = $datas['description'];
         $privacy = $datas['privacy'];
         
         $contentModel->editPost($name, $content, $privacy, $postId);
         $this->flash('Post edité avec succès');
         
         return $this->redirect($response, 'newprofile');
     }
     
     public function addContent($request, $response)
     {
         
         $errors = [];
         Validator::notEmpty()->validate($request->getParam('name')) || $errors['name'] = 'Veuillez remplir ce champ Titre';
         Validator::notEmpty()->validate($request->getParam('content')) || $errors['content'] = 'Veuillez remplir ce champ Contenus';
         if(empty($errors)) {
             $this->flash('Votre contenus a bien été envoyé');
             $add = $this->container->get('contentModel');
             $add->addContent();
         } else {
             $this->flash('Il y a une erreur', 'error');
             $this->flash($errors, 'errors');
         }
         

         return $this->redirect($response, 'ajouter');
     }
     
     public function debug($request, $response)
     {
         $datas = $request->getParsedBody();
         $datas2 = $request->getQueryParams();
         var_dump($_GET);
         var_dump($datas2);
         var_dump($datas);
     }
               

     // Upload photos / files
    
     public function postUpload($request, $response)
     {
         // Flash message
         
         $this->flash('Votre image a bien été envoyée');
         
         $uploadedFile = $request->getUploadedFiles();
         $directory = $this->container->get('uploaded_directory');
         // Single file upload
         $uploadedFile = $uploadedFile['myfile'];
         //var_dump($uploadedFile->getError());
         if($uploadedFile->getError() === UPLOAD_ERR_OK){
             $this->moveUpLoadedFile($directory, $uploadedFile);
             //$response->write('uploaded ' . $filename . '<br/>');
         }
         // for debugging purposes
         return $this->container->view->render($response, 'pages/upload.twig');
         //return $this->redirect($response, 'upload');
     }
     
     
     // Upload file to test it's data for the app.
     public function postTestUpload($request, $response)
     {
         $uploadedFile = $request->getUploadedFile();
         $directory = $this->container->get('uploaded_directory');
         
         $uploadedFile = $uploadedFile['myfile'];
         if($uploadedFile->getError() === UPLOAD_ERR_OK){
             $this->moveTestFile($directory, $uploadedFile);
         }
         return $this->container->view->render($response, 'pages/exif.twig');
     }
     
     function moveTestFile($directory, UploadedFile $uploadedFile)
     {
         $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
         $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
         $filename = sprintf('%s.%0.8s', $basename, $extension);
         
         $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . "quarantine" . DIRECTORY_SEPARATOR . $filename);
         
         return $filename;
     }
     
     
     /**
      * Moves the uploaded file to the upload directory and assigns it a unique name
      * to avoid overwriting an existing uploaded file.
      *
      * @param string $directory directory to which the file is moved
      * @param UploadedFile $uploaded file uploaded file to move
      * @return string filename of moved file
      */
     function moveUpLoadedFile($directory, UploadedFile $uploadedFile)
     {
         $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
         $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
         //$userFileName = $_POST['name'];
         //var_dump($userFileName);
         $filename = sprintf('%s.%0.8s', $basename, $extension);
         
         $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . "photos" . DIRECTORY_SEPARATOR . $filename);
         
         return $filename;
     }
     
    
    
}