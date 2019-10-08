<?php

namespace App\Models;

Class ImagesModel extends Model
{
                 
    public function fetchDatasPublic()
    {
        $sql = "SELECT markers.id, markers.name, images.filename, markers.address, markers.lng, 
                markers.lat, markers.altitude, images.upload_date, images.type, images.liked,
                images.height, images.width, images.size, members.name as user_name, images.groupimg_id, images.privacy              
                FROM markers 
                    INNER JOIN images 
                        ON markers.image_id = images.id
                    INNER JOIN members 
                        ON images.user_id = members.id
                WHERE images.privacy = 0";
        $dataImages = $this->executeQuery($sql);
        return $dataImages->fetchAll();
    }
    
    public function fetchDatasFriends($uid)
    {
        $sql = "SELECT markers.id, markers.name, images.filename, markers.address, markers.lng,
                markers.lat, markers.altitude, images.upload_date, images.type, images.liked,
                images.height, images.width, images.size, members.name as user_name, images.groupimg_id, images.privacy
                FROM markers
                    INNER JOIN images
                        ON markers.image_id = images.id
                    INNER JOIN members
                        ON images.user_id = members.id
					INNER JOIN friendship
						ON images.user_id = friendship.friend_b
                WHERE friendship.friend_a = ? AND images.privacy = 1";
        $dataImages = $this->executeQuery($sql, array($uid));
        return $dataImages->fetchAll();
    }
    
    // Fetch remaining "non-public" photos posted from the connected member, used in GMap.
    public function fetchMyImgs($uid)
    {
        $sql = "SELECT markers.id, markers.name, images.filename, markers.address, markers.lng,
                markers.lat, markers.altitude, images.upload_date, images.type, images.liked,
                images.height, images.width, images.size, members.name as user_name, images.groupimg_id, images.privacy
                FROM markers
                    INNER JOIN images
                        ON markers.image_id = images.id
                    INNER JOIN members
                        ON images.user_id = members.id
                WHERE images.user_id = ? 
                AND images.privacy = 1 OR images.privacy = 2";
        $dataImages = $this->executeQuery($sql, array($uid));
        return $dataImages->fetchAll();
    }
    
    // Fetch every photos posted from the connected member, used in Profile Page.
    public function fetchAllMyImgs($uid)
    {
        $sql = "SELECT markers.id, markers.name, images.filename, markers.address, markers.lng,
                markers.lat, markers.altitude, images.upload_date, images.type, images.liked,
                images.height, images.width, images.size, members.name as user_name, images.groupimg_id, images.privacy
                FROM markers
                    INNER JOIN images
                        ON markers.image_id = images.id
                    INNER JOIN members
                        ON images.user_id = members.id
                WHERE images.user_id = ?";
        $dataImages = $this->executeQuery($sql, array($uid));
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
    
    public function getFilenameId($filename)
    {
        $sql = "SELECT id FROM images WHERE filename = ?";
        $id = $this->executeQuery($sql, array($filename));
        return $id->fetch();
    }
           
    public function addGeoDatas($lng, $lat, $alt, $imgId)
    {
        $sql = "INSERT INTO markers (name, address, lng, lat, altitude, image_id) VALUES(?, 'placeholder', ?, ?, ?, ?)";
        // Title goes to controller soon.
        $title = $_POST['name'];
        $this->executeQuery($sql, array($title, $lng, $lat, $alt, $imgId));
    }
    
    public function addInfos($filename, $height, $width, $size, $type, $user, $groupImg, $privacy){
        $sql = "INSERT INTO images (filename, height, width, size, type, upload_date, user_id, groupimg_id, privacy, thumbnail_base64) 
                VALUES(?, ?, ?, ?, ?, NOW(), ?, ?, ?, 'placeholder')";
        $this->executeQuery($sql, array($filename, $height, $width, $size, $type, $user, $groupImg, $privacy));
    }
    
    public function fetchImgInfos(){
        $sql = "SELECT * FROM images WHERE 1";
        $imgDatas = $this->executeQuery($sql);
        return $imgDatas->fetchAll();
    }
    
    public function likeImage($imgId){
        $sql = "UPDATE images
                SET liked = liked + 1
                WHERE id = ?";
        $this->executeQuery($sql, array($imgId));
    }
    
    public function mostLikedImgs(){
        $sql = "SELECT id, filename, liked 
                FROM images 
                WHERE privacy = 0
				ORDER BY liked DESC 
                LIMIT 0,3";
        $likedImages = $this->executeQuery($sql);
        return $likedImages->fetchAll();
    }
    
    public function reportImage($imgId) {
        $sql = "UPDATE images
                SET reported = reported + 1
                WHERE id = ?";
        $this->executeQuery($sql, array($imgId));
    }
    
    // Moderation
    // Administration
    
    
}