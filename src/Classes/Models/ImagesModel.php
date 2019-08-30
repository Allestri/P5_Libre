<?php

namespace App\Models;

Class ImagesModel extends Model
{
    
    public function testContent()
    {
        $testDatas = "Hello world";
        return $testDatas;
    }
      
    
    public function fetchMarkersM()
    {
        $sql = "SELECT * FROM markers WHERE 1";
        $markers = $this->executeQuery($sql);
        return $markers->fetchAll();
    }
    
    public function fetchDatas()
    {
        $sql = "SELECT markers.id, markers.name, images.filename, markers.address, markers.lng, 
                markers.lat, markers.altitude, images.upload_date, images.type, 
                images.height, images.width, images.size, members.name as user_name              
                FROM markers 
                    INNER JOIN images 
                        ON markers.image_id = images.id
                    INNER JOIN members 
                        ON images.user_id = members.id";
        $dataImages = $this->executeQuery($sql);
        return $dataImages->fetchAll();
    }
    
    public function linkId()
    {
        $sql = "SELECT MAX(id) AS id FROM images";
        $id = $this->executeQuery($sql);
        return $id->fetch();
    }
           
    public function addGeoDatas($lng, $lat, $alt, $imgId)
    {
        $sql = "INSERT INTO markers (name, address, lng, lat, altitude, image_id) VALUES(?, 'placeholder', ?, ?, ?, ?)";
        // Title goes to controller soon.
        $title = $_POST['name'];
        $this->executeQuery($sql, array($title, $lng, $lat, $alt, $imgId));
    }
    
    public function addInfos($filename, $height, $width, $size, $type, $user){
        $sql = "INSERT INTO images (filename, height, width, size, type, upload_date, user_id, thumbnail_base64) VALUES(?, ?, ?, ?, ?, NOW(), ?, 'placeholder')";
        $this->executeQuery($sql, array($filename, $height, $width, $size, $type, $user));
    }
    
    public function fetchImgInfos(){
        $sql = "SELECT * FROM images WHERE 1";
        $imgDatas = $this->executeQuery($sql);
        return $imgDatas->fetchAll();
    }
    
}