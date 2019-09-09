<?php


namespace App\Controllers;

class ImagesController extends ContentController
{
    protected $container;
    
    public function __construct($container) 
    {
        $this->container = $container;
    }
    
    // Personal notes - image model construct
    // NOT FINISHED
    
    // Formulaire
    public function getForm($request, $response)
    {
        if(isset($_SESSION['username'])){
            $username = $_SESSION['username'];
            $member['profile'] = $username;
            return $this->render($response, 'pages/upload.twig', $member);
        } else {
            return $this->render($response, 'pages/upload.twig');
        }
    }
    
    // Google Maps
    public function displayMap($request, $response)
    {
        return $this->container->view->render($response, 'pages/map.twig');
    }
        
    public function fetchMarkers()
    {
        $imageModel = $this->container->get('imagesModel');        
        
        $datasImages = $imageModel->fetchDatas();
        
        $directory = $this->container->get('json_directory');

        $json = json_encode($datasImages);
        $filename = $directory . DIRECTORY_SEPARATOR . "datasimages.json";
        
        file_put_contents($filename, $json);
    }
    
    public function fetchMarkersRest()
    {
        $imageModel = $this->container->get('imagesModel');
        
        // Controller WIP
        // ( friends, public, private )
        // Displaying friends markers
        if(isset($_SESSION['uid'])){
            
            $uid = $_SESSION['uid'];

            $publicImgs = $imageModel->fetchDatasPublic();
            $friendsImgs = $imageModel->fetchDatasFriends($uid);
            $myPrivateImgs = $imageModel->fetchMyImgs($uid);
            $datasImgs = array_merge($publicImgs, $friendsImgs, $myPrivateImgs);
        } else {
            // Displaying public images only.
            $datasImgs = $imageModel->fetchDatasPublic();
        }
         
        echo json_encode($datasImgs);
    }
    
    /* 
     * Gets the user who uploaded the content 
     * returns his unique ID
    */
    public function getUser()
    {   
        $userId = $_SESSION['uid'];
        var_dump($_SESSION);
        return $userId;
    }    
         
    public function manageExif($request, $response)
    {

        $imageModel = $this->container->get('imagesModel');
        
        
        //$this->postUpload($request, $response);
        $uploadedFile = $request->getUploadedFiles();
        $uploadedFile = $uploadedFile['image'];
        $directory = $this->container->get('uploaded_directory');
                
        $filename = $this->moveUpLoadedFile($directory, $uploadedFile);
        
        //var_dump($uploadedFile);
        
        // Gets the user id who uploaded the photo
        $user = $this->getUser();
        
        // Picture infos ( size, height, width)
        $picInfos = $this->putPictureInfos($filename, $directory);
        
        // Thumbnail
        $thumbnail = $this->getThumbnail($filename, $directory);
        //var_dump($thumbnail);
        
        // EXIF
        $coordinates = $this->putExif($filename, $directory);
        $hasExif = $this->exifReady($filename, $directory);
        //var_dump($hasExif);
        
        // Insert info data (including base64 thumbnail content) 
        $imageModel->addInfos($filename, $picInfos['height'], $picInfos['width'], $picInfos['size'], $picInfos['type'], $user);
        
        // Fetch the file's unique ID
        $imageId = $imageModel->linkId();
        
        // Insert exif Datas if there is exif available.
        if($hasExif){
            $imageModel->addGeoDatas($coordinates['longitude'], $coordinates['latitude'], $coordinates['altitude'], $imageId['id']);
        }
        
        return $this->container->view->render($response, 'pages/upload.twig');
    }
        
    // Test if Exif or not.
    public function exifReady($file, $directory){
        
        $exif = @exif_read_data($directory. DIRECTORY_SEPARATOR . "photos" . DIRECTORY_SEPARATOR .$file, 0, true);
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
    
    public function seekExif($file, $directory){
        
        $exif = @exif_read_data($directory. DIRECTORY_SEPARATOR . "photos" . DIRECTORY_SEPARATOR . $file, 0, true);
        return $exif;
    }
    
    public function putExif($file, $directory){

        $exif = $this->seekExif($file, $directory);
        //var_dump($exif);

        // Checks geo coordinates
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
            
            
            $Altitude = $this->getCoords($exif['GPS']['GPSAltitude']);
                      
            
            // Return coordinates
            $result['latitude'] = $Latitude;
            $result['longitude'] = $Longitude;
            $result['altitude'] = $Altitude;
            
            var_dump($result);
            return $result;
        } else {
            echo 'Votre fichier ne contient pas de données de géolocalisation';
        }
    }
    
    public function getThumbnail($file, $directory){
                
        $image = exif_thumbnail($directory. DIRECTORY_SEPARATOR . "photos" . DIRECTORY_SEPARATOR . $file, $width, $height, $type);
        //$baseEncode = base64_encode($image);
        file_put_contents($directory. DIRECTORY_SEPARATOR . "thumbnails" . DIRECTORY_SEPARATOR . $file, $image);
        if ($image) {
            // send image data to the browser:
            echo "Thumbnail available</br>";
            echo "<img width='$width' height='$height' src='data:image/gif;base64,".base64_encode($image)."'>";
            // return base64 content
            //return $baseEncode;    
        }
        else {
            // handling error:
            print 'No thumbnail available';
        }
    }
    
    public function putPictureInfos($file, $directory){
        
        // Fetch and return Exif datas
        $exif = $this->seekExif($file, $directory);
        
        if(isset($exif)){
            
            $pictureSize = $exif['FILE']['FileSize'];
            $pictureHeight = $exif['COMPUTED']['Height'];
            $pictureWidth = $exif['COMPUTED']['Width'];
            $pictureType = $exif['FILE']['MimeType'];
            
            // Return infos
            $result['size'] = $pictureSize;
            $result['height'] = $pictureHeight;
            $result['width'] = $pictureWidth;
            $result['type'] = $pictureType;
            
            var_dump($result);
            return $result;
        } else {
            echo "Pas de données";
        }
    }    
    
}
    
    