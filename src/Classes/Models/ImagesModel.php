<?php

namespace App\Models;

Class ImagesModel extends Model
{
                 
    public function fetchDatasPublic()
    {
        $sql = "SELECT markers.id, markers.lng, markers.lat, 
                images.filename, images.groupimg_id, posts.privacy              
                FROM markers 
                    INNER JOIN images 
                        ON markers.image_id = images.id
                    INNER JOIN posts
                    	ON markers.image_id = posts.image_id
                WHERE posts.privacy = 0";
        $dataImages = $this->executeQuery($sql);
        return $dataImages->fetchAll();
    }
    
    public function fetchDatasFriends($uid)
    {
        $sql = "SELECT markers.id, markers.lng, markers.lat,
                images.filename, images.groupimg_id, posts.privacy
                FROM markers
                    INNER JOIN images
                        ON markers.image_id = images.id
					INNER JOIN posts
                    	ON markers.image_id = posts.image_id
					INNER JOIN friendship
						ON images.user_id = friendship.friend_b
                WHERE friendship.friend_a = ? AND friendship.status = 'friend' AND posts.privacy = 1";
        $dataImages = $this->executeQuery($sql, array($uid));
        return $dataImages->fetchAll();
    }
    
    // Fetch remaining "non-public" photos posted from the connected member, used in GMap.
    public function fetchMyImgs($uid)
    {
        $sql = "SELECT markers.id, markers.lng, markers.lat,
                images.filename, images.groupimg_id, posts.privacy
                FROM markers
                    INNER JOIN images
                        ON markers.image_id = images.id
                    INNER JOIN posts
                    	ON markers.image_id = posts.image_id
                WHERE images.user_id = ? 
                AND (posts.privacy = 1 OR posts.privacy = 2)";
        $dataImages = $this->executeQuery($sql, array($uid));
        return $dataImages->fetchAll();
    }
    
    // Fetch every photos posted from the connected member, used in Profile Page.
    public function fetchAllMyImgs($uid)
    {
        $sql = "SELECT posts.id, posts.name, posts.liked, posts.reported,
                members.name as user_name,
                images.filename, images.upload_date, images.type
                FROM posts
                    INNER JOIN images
                        ON posts.image_id = images.id
                    INNER JOIN members
                        ON posts.user_id = members.id
                WHERE posts.user_id = ?";
        $dataImages = $this->executeQuery($sql, array($uid));
        return $dataImages->fetchAll();
    }
    
    public function fetchImgsInfos($markerId)
    {
        $sql = "SELECT markers.name, markers.address, markers.lng,markers.lat, markers.altitude,
                images.height, images.width, images.size, images.type, DATE_FORMAT(images.upload_date, '%d/%m/%Y à %Hh%imin%') as upload_date, images.liked, 
                members.name as author, members.avatar_file
                FROM markers
                    INNER JOIN images
                        ON markers.image_id = images.id
                    INNER JOIN members
                        ON images.user_id = members.id
                WHERE markers.id = ?";
        $infosImages = $this->executeQuery($sql, array($markerId));
        return $infosImages->fetch();
    }
    
    public function fetchImgsInfosNew($markerId)
    {
        $sql = "SELECT markers.name, markers.lng, markers.lat, markers.altitude,
                posts.name, posts.content as description, posts.liked, posts.reported, 
                images.height, images.width, images.size, images.type, DATE_FORMAT(images.upload_date, '%d/%m/%Y à %Hh%i') as upload_date,
                members.name as author, avatars.avatar_file as author_avatar
                FROM markers
                    INNER JOIN posts
                        ON markers.id = posts.marker_id
                    INNER JOIN members
                        ON posts.user_id = members.id
                    INNER JOIN images
                        ON markers.image_id = images.id
                    LEFT OUTER JOIN avatars
                        ON members.id = avatars.user_id
                WHERE markers.id = ?";
        $infosImages = $this->executeQuery($sql, array($markerId));
        return $infosImages->fetch();
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
    
    public function linkMarkerId()
    {
        $sql = "SELECT MAX(id) AS id FROM markers";
        $markerId = $this->executeQuery($sql);
        return $markerId->fetch();
    }
       
           
    public function addGeoDatas($lng, $lat, $alt, $imgId)
    {
        $sql = "INSERT INTO markers (name, address, lng, lat, altitude, image_id) VALUES(?, 'placeholder', ?, ?, ?, ?)";
        // Title goes to controller soon.
        $title = $_POST['name'];
        $this->executeQuery($sql, array($title, $lng, $lat, $alt, $imgId));
    }
    
    public function addInfos($filename, $height, $width, $size, $type, $user, $groupImg, $privacy){
        $sql = "INSERT INTO images (filename, height, width, size, type, upload_date, user_id, groupimg_id, privacy) 
                VALUES(?, ?, ?, ?, ?, NOW(), ?, ?, ?)";
        $this->executeQuery($sql, array($filename, $height, $width, $size, $type, $user, $groupImg, $privacy));
    }
    
    public function fetchImgInfos(){
        $sql = "SELECT * FROM images";
        $imgDatas = $this->executeQuery($sql);
        return $imgDatas->fetchAll();
    }
    
    public function getFilenameId($filename)
    {
        $sql = "SELECT id FROM images WHERE filename = ?";
        $id = $this->executeQuery($sql, array($filename));
        return $id->fetch();
    }   
    
    public function mostLikedImgs(){
        $sql = "SELECT posts.id, images.filename, count(likes.id) as liked
                FROM posts
                INNER JOIN images
                    ON posts.image_id = images.id
                INNER JOIN likes
                    ON posts.id = likes.post_id
                GROUP BY posts.id
                ORDER BY liked DESC
                LIMIT 0,3";
        $likedImages = $this->executeQuery($sql);
        return $likedImages->fetchAll();
    }
    
    public function deleteImage($imgId, $uid) {
        $sql = "DELETE FROM images
                WHERE id = ?
                AND user_id = ?";
        $this->executeQuery($sql, array($imgId, $uid));
    }
    
    // Deprecated
    public function reportImage($imgId) {
        $sql = "UPDATE images
                SET reported = reported + 1
                WHERE id = ?";
        $this->executeQuery($sql, array($imgId));
    }
    
    public function likeImage($imgId){
        $sql = "UPDATE images
                SET liked = liked + 1
                WHERE id = ?";
        $this->executeQuery($sql, array($imgId));
    }
    

   
    
    
}