<?php


namespace App\Controllers;

class ImagesController extends ContentController
{
    protected $container;
    
    public function __construct($container) {
        $this->container = $container;
    }
    
    
    // NOT FINISHED
    
    // Formulaire
    public function getForm($request, $response)
    {
        return $this->render($response, 'pages/uploadng.twig');
    }
    
    
    public function manageExif($request, $response)
    {
        
        $imageModel = $this->container->get('imagesModel');
        
        
        //$this->postUpload($request, $response);
        $uploadedFile = $request->getUploadedFiles();
        $uploadedFile = $uploadedFile['image'];
        $directory = $this->container->get('uploaded_directory');
        
        
        $filename = $this->moveUpLoadedFile($directory, $uploadedFile);
        
        var_dump($uploadedFile);
        
        // EXIF
        $coordinates = $this->putExif($filename, $directory);
        $exifValides = $this->exifReady($filename, $directory);
        var_dump($exifValides);
        
        // Insert exif Datas if there is exif available.
        if($exifValides){
            $imageModel->addDatas($coordinates['longitude'], $coordinates['latitude']);
        }
        
        return $this->container->view->render($response, 'pages/uploadng.twig');
    }
        
        
        /*
        if(isset($_FILES['myfile'])){
            
            //pre_r($_FILES);
            
            
            
            $phpFileUploadErrors = array(
                0 => 'There is no error, the file uploaded with success',
                1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                3 => 'The uploaded file was only partially uploaded',
                4 => 'No file was uploaded',
                6 => 'Missing a temporary folder',
                7 => 'Failed to write file to disk.',
                8 => 'A PHP extension stopped the file upload.',
            );
            
            
            // Contrôle une liste extension valides.
            $ext_error = false;
            $extensions = array('jpg', 'JPG', 'jpeg', 'gif', 'png');
            $file_ext = explode('.', $_FILES['myfile']['name']);
            $file_ext = end($file_ext);
            //pre_r($file_ext);
            if(!in_array($file_ext, $extensions)){
                $ext_error = true;
            }
            
            // Si l'erreur n'est pas egale à 0
            if($ext_error){
                echo 'Type de fichier invalide';
            } elseif ($_FILES['myfile']['error'] > 0){
                echo $phpFileUploadErrors[$_FILES['myfile']['error']];
            } else {
                echo $phpFileUploadErrors[$_FILES['myfile']['error']];
            }
                               
            $tmp_name = $_FILES['myfile']['tmp_name'];
            $dir_folder = $_SERVER['DOCUMENT_ROOT'];

            
            // Unique file ID
            $fid = date('H_i_s');
       
           
            // Rename the file
            $name = "{$_POST['titre']}.{$file_ext}";
            //var_dump($name);
            
            
            
            
            // Check if a file already exists
            if(file_exists('images/'. $name)){
                echo "</br>Le fichier existe</br>";
                //renameFile($fid);
                $name = "{$_POST['titre']}_{$fid}.{$file_ext}";
                move_uploaded_file($tmp_name, 'images/'. $name);
                
                // EXIF
                $coordinates = putExif($name);
                $exifValides = exifReady($name);
                var_dump($exifValides);
               // Insert Datas from function
                if($exifValides){
                    $imageModel->insertDatas($coordinates['longitude'], $coordinates['latitude']);
                }
            } else {
                echo "</br>Le nom du fichier est disponible</br>";
                
                $coordinates = putExif($name);
                $exifValides = exifReady($name);
                var_dump($exifValides);
                if($exifValides){
                    $imageModel->insertDatas($coordinates['longitude'], $coordinates['latitude']);
                }
                echo "</br>Fichier uploadé avec succès !";
            }
        }
        */
    
    
    // Test if Exif or not.
    public function exifReady($file, $directory){
        $exif = @exif_read_data($directory. DIRECTORY_SEPARATOR . $file, 0, true);
        $hasExif = null;
        if(isset($exif['GPS'])){
            $hasExif = true;
        } else {
            $hasExif = false;
        }
        return $hasExif;
    }
    
    // Get the coordinates.
    public function getCoords($expr){
        $coords = explode('/', $expr);
        return $coords[0] / $coords[1];
    }
    
    public function putExif($file, $directory){

        $exif = @exif_read_data($directory. DIRECTORY_SEPARATOR . $file, 0, true);
        
        if(isset($exif['GPS'])){
            
            $GPSLatitudeRef = $exif['GPS']['GPSLatitudeRef'];
            $GPSLongitudeRef = $exif['GPS']['GPSLongitudeRef'];
            
            // get hemi multiplier
            $latM = 1;
            $longM = 1;
            
            //$flip = ($longitude == 'W' || $latitude == 'S') ? "-" : null;
            if($GPSLatitudeRef == 'S'){
                $latM = -1;
            }
            if($GPSLongitudeRef == 'W'){
                $longM = -1;
            }
            
            $GPSLongDegrees = $this->getCoords($exif['GPS']['GPSLongitude'][0]);
            $GPSLongMinutes = $this->getCoords($exif['GPS']['GPSLongitude'][1]);
            $GPSLongSeconds = $this->getCoords($exif['GPS']['GPSLongitude'][2]);
            
            $Longitude = $longM * ($GPSLongDegrees + $GPSLongMinutes / 60 + $GPSLongSeconds / 3600);
            //($GPSLongDegrees + $GPSLongMinutes / 60 + $GPSLongSeconds / 3600);
            
            $GPSLatDegrees = $this->getCoords($exif['GPS']['GPSLatitude'][0]);
            $GPSLatMinutes = $this->getCoords($exif['GPS']['GPSLatitude'][1]);
            $GPSLatSeconds = $this->getCoords($exif['GPS']['GPSLatitude'][2]);
            
            $Latitude = $latM *($GPSLatDegrees + $GPSLatMinutes / 60 + $GPSLatSeconds / 3600);
                      
            
            // Return coordinates
            $result['latitude'] = $Latitude;
            $result['longitude'] = $Longitude;
            var_dump($result);
            return $result;
        } else {
            echo 'Votre fichier ne contient pas de données EXIF';
        }
    }
    
    
}
    
    