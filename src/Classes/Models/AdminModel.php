<?php 

namespace App\Models;

class AdminModel extends Model
{
    
    public function countReports(){
        $sql = "SELECT COUNT(id) as reportsNbr FROM posts WHERE reported > 0";
        $reportsNbr = $this->executeQuery($sql);
        return $reportsNbr->fetch();
    }
    
    public function getReports(){
        $sql = "SELECT posts.id, posts.name, posts.content, images.filename, posts.liked, posts.reported
                FROM posts
                INNER JOIN images
                    ON posts.image_id = images.id
                WHERE posts.reported > 0";
        $reports = $this->executeQuery($sql);
        return $reports->fetchAll();
    }
    
    public function getSelectedReport($postId) {
        $sql = "SELECT posts.id, posts.name, members.name as author, posts.content
                FROM posts
                INNER JOIN members
                ON posts.user_id = members.id
                WHERE posts.id = ?";
        $reportedImg = $this->executeQuery($sql, array($postId));
        return $reportedImg->fetch();
    }
    
    public function editPost($name, $content, $postId){
        $sql = "UPDATE posts
                SET name = ?, content = ?, reported = 0
                WHERE id= ?";
        $this->executeQuery($sql, array($name, $content, $postId));
    }
    
    public function clearReports($imgId) {
        $sql = "UPDATE images 
                SET reported = 0
                WHERE id = ?";
        $this->executeQuery($sql, array($imgId));
    }
    
    public function deleteImage($postId){
        $sql = "DELETE FROM posts
                WHERE id = ?";
        $this->executeQuery($sql, array($postId));
    }   

}