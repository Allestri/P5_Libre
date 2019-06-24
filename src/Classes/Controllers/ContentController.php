<?php

namespace App\Controllers;

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
        return $this->container->view->render($response, 'home.twig');
    }
    
    
    public function getContent($request, $response, $args)
     {
     
     $datas = $this->container->get('contentModel');
     
     $args['datas'] = $datas->getContent();
     //var_dump($datas);
     
     
     // get the template renderer and pass response and datas to the template file.
     return $this->container->view->render($response, 'content.twig', $args);
     }
     
     
     // Formulaire
     public function getForm($request, $response)
     {
         $flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : [];
         $_SESSION['flash'] = [];
         return $this->render($response, 'pages/upload.twig', ['flash' => $flash]);
     }
     
     public function getAddForm($request, $response)
     {
         return $this->container->view->render($response, 'pages/addForm.twig');
     }
     
     public function addContent($request, $response)
     {
         $this->flash('Votre contenus a bien été envoyé');
         $add = $this->container->get('contentModel');
         $add->addContent();
         return $this->redirect($response, 'ajouter');
     }
     
     // Google Maps
     public function displayMap($request, $response)
     {
         return $this->container->view->render($response, 'pages/map.twig');
     }
          

     // Upload photos / files
    
     public function postUpload($request, $response, $args)
     {
         // Flash message
         
         $_SESSION['flash'] = [
             'success' => 'Votre fichier a bien été envoyé'
         ];
         
         
         $uploadedFile = $request->getUploadedFiles();
         $directory = $this->container->get('uploaded_directory');
         var_dump($directory);
         // Single file upload
         $uploadedFile = $uploadedFile['myfile'];
         //var_dump($uploadedFile->getError());
         if($uploadedFile->getError() === UPLOAD_ERR_OK){
             $filename = $this->moveUpLoadedFile($directory, $uploadedFile);
             $response->write('uploaded ' . $filename . '<br/>');
         }
         
         
         return $this->redirect($response, 'upload');
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
         var_dump($directory);
         $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
         $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
         $userFileName = $_POST['name'];
         var_dump($userFileName);
         $filename = sprintf('%s.%0.8s', $basename, $extension);
         
         $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
         
         return $filename;
     }
     
    
    
}