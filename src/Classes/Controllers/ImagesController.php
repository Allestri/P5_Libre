<?php


namespace App\Controllers;

class ImagesController extends ContentController
{
    protected $container;
    
    public function __construct($container) 
    {
        $this->container = $container;
    }
    
    // Form
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
        
    public function getTestForm($request, $response)
    {
        if(isset($_SESSION['username'])){
            $username = $_SESSION['username'];
            $member['profile'] = $username;
            return $this->render($response, 'pages/exif.twig', $member);
        } else {
            return $this->render($response, 'pages/exif.twig');
        }
    }
    
    // Google Maps
    public function displayMap($request, $response)
    {
        return $this->container->view->render($response, 'pages/map.twig');
    }
        
    public function fetchMarkersRest()
    {
        $imageModel = $this->container->get('imagesModel');
        
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
    
    /* Social functionalities */
    
    public function retrieveImageId($request, $response)
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
    
    public function commentImage($request, $response)
    {
        $datas = $request->getParsedBody();
        $uid = $datas['uid'];
        
        $content = $datas['content'];
        $imgId = $datas['imgId'];
        
        
        $contentModel = $this->container->get('contentModel');
        $contentModel->addComment($uid, $content, $imgId);
    }
    
    // Gets info from a given marker
    // Then gets any comments attached on it.
    public function getInfos($request, $response)
    {
        $imageModel = $this->container->get('imagesModel');
        $contentModel = $this->container->get('contentModel');
        
        $datas = $request->getQueryParams();
        $markerId = $datas['id'];
        $userId = $_SESSION['uid'];
        
        $datas = $imageModel->fetchImgsInfosNew($markerId);
        $likes = $contentModel->getLikes($markerId);
        $datas = array_merge($datas, $likes);
        $datas['comments'] = $contentModel->getCommentsNew($markerId);
        $mylikes = $contentModel->getMyLikes($markerId, $userId);
        $datas = array_merge($datas, $mylikes);
        
        echo json_encode($datas);
    }
           
    // Account page - fetch member's photos.
    public function getMyPhotos()
    {
        $imageModel = $this->container->get('imagesModel');
        $uid = $_SESSION['uid'];
        
        $myPhotos = $imageModel->fetchAllMyImgs($uid);
        
        echo json_encode($myPhotos);
    }            
    
    /* 
     * Gets the user who uploaded the content 
     * returns his unique ID
    */
    public function getUser()
    {   
        $userId = $_SESSION['uid'];
        return $userId;
    }
       
      
    // Tests if the uploaded file contains exif and datas needed on the app.
    public function testExif($request, $response)
    {
        $directory = $this->container->get('uploaded_directory');
        $uploadedFile = $request->getUploadedFiles();

        // Single file upload /*
        $uploadedFile = $uploadedFile['myimage'];
        if($uploadedFile->getError() === UPLOAD_ERR_OK){
            $filename = $this->moveTestFile($directory, $uploadedFile);
        }
        $exif = @exif_read_data($directory. DIRECTORY_SEPARATOR . "quarantine" . DIRECTORY_SEPARATOR . $filename, 0, true);
        var_dump($exif);    
        // Checks Geo data
        if(isset($exif['GPS']['GPSLatitudeRef'], $exif['GPS']['GPSLongitudeRef'])){
            $hasGeoExif = true;
        } else {
            $hasGeoExif = false;
        }
        // Checks thumbnail
        if(isset($exif['THUMBNAIL'])){
            $hasThumbnail = true;
        } else {
            $hasThumbnail = false;
        }
        // Checks image datas
        if(isset($exif['COMPUTED']) || ($exif)){
            $hasInfos = true;
        } else {
            $hasInfos = false;
        }
        
        // Checks hasexif variables then display a flash status message.
        /*
        if(!$hasGeoExif && !$hasThumbnail && !$hasInfos) {
            $this->flash('Votre image est totalement incompatible avec cette application', 'error');
        } elseif (!$hasGeoExif || !$hasThumbnail || !$hasInfos) {
            $this->flash('Certaines infos manquent pour une compatiblité complète', 'warning');
        } else {
            $this->flash('Votre image est entièrement compatible avec cette application');
        }
        */
        

        $_SESSION['filename'] = $filename;
        // If one is false, deletes the files from the server.
        // If not, moves it to the final directory.
        /*
        $photoPath = $directory . DIRECTORY_SEPARATOR . "quarantine" . DIRECTORY_SEPARATOR . $filename;
        if(!$hasGeoExif || !$hasInfos || !$hasThumbnail){
            unlink($photoPath);
        }
        */
        
        // Variables for test exif indicators
        $args['geodata'] = $hasGeoExif;
        $args['thumbnail'] = $hasThumbnail;
        $args['info'] = $hasInfos;
        
        echo json_encode($args);
        
        //return $this->container->view->render($response, 'pages/exif.twig');
    }
             
    public function manageExif($request, $response)
    {

        $imageModel = $this->container->get('imagesModel');
        $contentModel = $this->container->get('contentModel');
        
        // Get form datas
        $datas = $request->getParsedBody();
        $privacy = $datas['privacy'];
        $name = $datas['name'];
        $description = $datas['description'];
        
        $directory = $this->container->get('uploaded_directory');
        $filename = $_SESSION['filename'];
        
        $oldPath = $directory . DIRECTORY_SEPARATOR . "quarantine" . DIRECTORY_SEPARATOR . $filename;
        $newPath = $directory . DIRECTORY_SEPARATOR . "photos" . DIRECTORY_SEPARATOR . $filename;
        var_dump($filename);
        
        rename($oldPath, $newPath);

        $exif = $this->seekExif($filename, $directory, "photos");                
               
        // Gets the user id who uploaded the photo
        $user = $this->getUser();
        
        // Picture infos ( size, height, width)
        $picInfos = $this->getPictureInfos($exif);
        
        $groupImg = $this->manageDevice($exif);
        
        // Thumbnail
        $thumbnail = $this->getThumbnail($filename, $directory, $exif);
        //var_dump($thumbnail);
        
        // EXIF
        $coordinates = $this->putExif($exif);
        //$hasExif = $this->exifReady($filename, $directory);
        //var_dump($hasExif);
        
        // Insert info data ( height, width, privacy .. )
        $imageModel->addInfos($filename, $picInfos['height'], $picInfos['width'], $picInfos['size'], $picInfos['type'], $user, $groupImg, $privacy);
        
        // Fetch the file's unique ID
        $imageId = $imageModel->linkId();
        
        // Insert exif Datas if there is exif available.
        $imageModel->addGeoDatas($coordinates['longitude'], $coordinates['latitude'], $coordinates['altitude'], $imageId['id']);
        // Fetch the matching marker id.
        $markerId = $imageModel->linkMarkerId();
        
        $contentModel->addPost($name, $description, $user, $imageId['id'], $markerId['id'], $privacy);
        
        $this->flash('Votre image a bien été mise en ligne');
        return $this->container->view->render($response, 'pages/exif.twig');
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
    
    // Fetch the device the photo is taken from.
    public function getDevice($exif)
    {
        $deviceBrand = null;
        if(isset($exif['IFD0'])){
            $deviceBrand = $exif['IFD0']['Make'];            
        }
        return $deviceBrand;
    }
    
    // Assigns a group depending on the device found.
    // Assigns 1 it's a DJI.
    // If null, assigns 4 ( Others )
    public function manageDevice($exif)
    {
        $device = $this->getDevice($exif);
        
        if(!empty($device)){
            if($device == "DJI"){
                // Drones
                $groupImg = 1;
            } else {
                // Smartphones
                $groupImg = 2;
            }
        } else {
            // "Others"
            $groupImg = 4;
        }
        return $groupImg;
    }
    
    // Get the coordinates.
    public function getCoords($expr){
        $coords = explode('/', $expr);
        return $coords[0] / $coords[1];
    }
    
    public function seekExif($file, $directory, $path){
        
        $exif = @exif_read_data($directory. DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $file, 0, true);
        return $exif;
    }
    
    
    public function putExif($exif){

        // Checks geo coordinates
        if(isset($exif['GPS']['GPSLatitudeRef'], $exif['GPS']['GPSLongitudeRef'])) {
            
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
            
            return $result;
        } else {
            // Message flash erreur !
            echo '<p>Votre fichier ne contient pas de données de géolocalisation</p>';
        }
    }
    
    public function getThumbnail($file, $directory, $exif){
        
                        
        $image = exif_thumbnail($directory. DIRECTORY_SEPARATOR . "photos" . DIRECTORY_SEPARATOR . $file, $width, $height, $type);
        file_put_contents($directory. DIRECTORY_SEPARATOR . "thumbnails" . DIRECTORY_SEPARATOR . $file, $image);
        
        if ($image) {
            // send image data to the browser:
            echo "Thumbnail available</br>";
            echo "<img width='$width' height='$height' src='data:image/gif;base64,".base64_encode($image)."'>";
            // return base64 content
        }
        else {
            // handling error:
            print 'No thumbnail available';
        }
        
    }
    
    public function getPictureInfos($exif){
               
        if(isset($exif)){
            
            $pictureSize = $exif['FILE']['FileSize'];
            $pictureHeight = $exif['COMPUTED']['Height'];
            $pictureWidth = $exif['COMPUTED']['Width'];
            $pictureType = $exif['FILE']['MimeType'];
            
            // Assigns infos
            $result['size'] = $pictureSize;
            $result['height'] = $pictureHeight;
            $result['width'] = $pictureWidth;
            $result['type'] = $pictureType;
            
            //$thumbnail = $this->createThumbnail($file, $directory, $pictureType, $pictureWidth, $pictureHeight);
            
            //var_dump($result);
            return $result;
        } else {
            echo "Pas de données";
        }
    }
    
    // ! Not using !
    
    public function createThumbnail($file, $directory, $ext, $fileWidth, $fileHeight)
    {
        // Gets image ratio
        $ratio = $this->getImageRatio($fileWidth, $fileHeight);
        
        $thumbWidth = 160;
        $thumbHeight = round($thumbWidth / $ratio);
        
        $path = $directory . DIRECTORY_SEPARATOR . "thumbnails" . DIRECTORY_SEPARATOR . $file;
        var_dump($path);
        $thumbnail = imagecreatetruecolor(160, $thumbHeight);
        //$ext = exif_imagetype($file);
        switch($ext){
            case 'image/jpg' || 'image/jpeg':
                $img = imageCreateFromJpeg($directory. DIRECTORY_SEPARATOR . "photos" . DIRECTORY_SEPARATOR . $file);
                break;
            case 'image/png':
                $img = imageCreateFromPng($directory. DIRECTORY_SEPARATOR . "photos" . DIRECTORY_SEPARATOR . $file);
                break;
        }
        imagecopyresized($thumbnail, $img, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $fileWidth, $fileHeight);
        switch($ext){
            case 'image/jpg' || 'image/jpeg':
                imagejpeg($thumbnail, $path, 100);
                break;
            case 'image/png':
                imagepng($thumbnail, $path, 100);
                break;
            default:
                imagejpeg($thumbnail, $path, 100);
        }
          
    }
    
    public function getImageRatio($width, $height)
    {
        $imageRatio = round($width / $height, 2);
        return $imageRatio;
    }
    
    
}
    
    