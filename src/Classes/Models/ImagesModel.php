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
        $sql = "SELECT markers.id, markers.name, markers.address, markers.lng, 
                markers.lat, markers.altitude, images.upload_date, markers.type, 
                images.height, images.width, images.size 
                FROM markers INNER JOIN images ON markers.image_id = images.id";
        $dataImages = $this->executeQuery($sql);
        return $dataImages->fetchAll();
    }
           
    public function addGeoDatas($lng, $lat, $alt)
    {
        $sql = "INSERT INTO markers (name, address, lng, lat, altitude, upload_date, type) VALUES(?, 'placeholder', ?, ?, ?, NOW(), 'jpeg')";
        // Title goes to controller soon.
        $title = $_POST['name'];
        $this->executeQuery($sql, array($title, $lng, $lat, $alt));
    }
    
    public function addInfos($height, $width, $size, $type, $user){
        $sql = "INSERT INTO images (height, width, size, type, upload_date, user_id, thumbnail_base64) VALUES(?, ?, ?, ?, NOW(), ?, 'placeholder')";
        $this->executeQuery($sql, array($height, $width, $size, $type, $user));
    }
    
    public function fetchImgInfos(){
        $sql = "SELECT * FROM images WHERE 1";
        $imgDatas = $this->executeQuery($sql);
        return $imgDatas->fetchAll();
    }
    
}