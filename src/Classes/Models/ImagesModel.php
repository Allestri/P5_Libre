<?php

namespace App\Models;

Class ImagesModel extends Model
{
                 
    public function fetchDatasPublic()
    {
        $sql = "SELECT markers.id, markers.name, images.filename, markers.address, markers.lng, 
                markers.lat, markers.altitude, images.upload_date, images.type, 
                images.height, images.width, images.size, members.name as user_name, images.privacy              
                FROM markers 
                    INNER JOIN images 
                        ON markers.image_id = images.id
                    INNER JOIN members 
                        ON images.user_id = members.id
                WHERE images.privacy = 0";
        $dataImages = $this->executeQuery($sql);
        return $dataImages->fetchAll();
    }
    
    public function fetchDatasFriends()
    {
        $sql = "SELECT markers.id, markers.name, images.filename, markers.address, markers.lng,
                markers.lat, markers.altitude, images.upload_date, images.type,
                images.height, images.width, images.size, members.name as user_name, images.privacy
                FROM markers
                    INNER JOIN images
                        ON markers.image_id = images.id
                    INNER JOIN members
                        ON images.user_id = members.id
                WHERE images.privacy = 0 OR images.privacy = 1";
        $dataImages = $this->executeQuery($sql);
        return $dataImages->fetchAll();
    }  
    
    public function countImgMember($userId)
    {
        $sql = "SELECT count(*) as imgnbr FROM images WHERE user_id = ?";
        $imgNbr = $this->executeQuery($sql, array ($userId));
        return $imgNbr->fetch();
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
        $sql = "INSERT INTO images (filename, height, width, size, type, upload_date, user_id, groupimg_id, thumbnail_base64) VALUES(?, ?, ?, ?, ?, NOW(), ?, 1, 'placeholder')";
        $this->executeQuery($sql, array($filename, $height, $width, $size, $type, $user));
    }
    
    public function fetchImgInfos(){
        $sql = "SELECT * FROM images WHERE 1";
        $imgDatas = $this->executeQuery($sql);
        return $imgDatas->fetchAll();
    }
    
}