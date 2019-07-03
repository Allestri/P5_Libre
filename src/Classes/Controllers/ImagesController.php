<?php


namespace App\Controllers;

class ImagesController extends Controller
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
    
    // Test if Exif or not.
    public function exifReady($file){
        $exif = @exif_read_data('images/' . $file, 0, true);
        $hasExif = null;
        if(isset($exif['GPS'])){
            $hasExif = true;
        } else {
            $hasExif = false;
        }
        return $hasExif;
    }
    
    public function putExif($file){
        
        $exif = @exif_read_data('images/' . $file, 0, true);
        
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
            
            $GPSLongDegrees = getCoords($exif['GPS']['GPSLongitude'][0]);
            $GPSLongMinutes = getCoords($exif['GPS']['GPSLongitude'][1]);
            $GPSLongSeconds = getCoords($exif['GPS']['GPSLongitude'][2]);
            
            $Longitude = $longM * ($GPSLongDegrees + $GPSLongMinutes / 60 + $GPSLongSeconds / 3600);
            //($GPSLongDegrees + $GPSLongMinutes / 60 + $GPSLongSeconds / 3600);
            
            $GPSLatDegrees = getCoords($exif['GPS']['GPSLatitude'][0]);
            $GPSLatMinutes = getCoords($exif['GPS']['GPSLatitude'][1]);
            $GPSLatSeconds = getCoords($exif['GPS']['GPSLatitude'][2]);
            
            $Latitude = $latM *($GPSLatDegrees + $GPSLatMinutes / 60 + $GPSLatSeconds / 3600);
                      
            
            // Return coordinates
            $result['latitude'] = $Latitude;
            $result['longitude'] = $Longitude;
            
            return $result;
        } else {
            echo 'Votre fichier ne contient pas de données EXIF';
        }
    }
    
    

    
    
    
    
    
}