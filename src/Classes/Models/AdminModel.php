<?php 

namespace App\Models;

class AdminModel extends Model
{
    
    public function countReports(){
        $sql = "SELECT COUNT(id) as reportsNbr FROM images WHERE reported > 0";
        $reportsNbr = $this->executeQuery($sql);
        return $reportsNbr->fetch();
    }
    
    public function getReports(){
        $sql = "SELECT images.id, images.filename, images.liked, images.reported, markers.name
                FROM images
                INNER JOIN markers
                    ON images.id = markers.image_id
                WHERE reported > 0";
        $reports = $this->executeQuery($sql);
        return $reports->fetchAll();
    }
    
    public function getSelectedReport($imgId) {
        $sql = "SELECT images.id, members.name as author, markers.name
                FROM images
                INNER JOIN markers
                    ON images.id = markers.image_id
                INNER JOIN members
                    ON images.user_id = members.id
                WHERE images.id = ?";
        $reportedImg = $this->executeQuery($sql, array($imgId));
        return $reportedImg->fetch();
    }
    
    public function editPost($imgId){
        $sql = "UPDATE images
                SET reported = 0
                WHERE id= ?";
        $this->executeQuery($sql, array($imgId));
    }
    
    public function clearReports($imgId) {
        $sql = "UPDATE images 
                SET reported = 0
                WHERE id = ?";
        $this->executeQuery($sql, array($imgId));
    }
    
    public function deleteImage($imgId){
        $sql = "DELETE FROM images
                WHERE id = ?";
        $this->executeQuery($sql, array($imgId));
    }   

}