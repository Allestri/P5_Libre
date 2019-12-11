<?php 

namespace App\Models;

class AdminModel extends Model
{
    
    public function countReports(){
        $sql = "SELECT COUNT(id) as reportsNbr FROM post_reports";
        $reportsNbr = $this->executeQuery($sql);
        return $reportsNbr->fetch();
    }
    
    public function getReports(){
        $sql = "SELECT posts.id, posts.name, posts.content, images.filename
                FROM posts
                INNER JOIN images
                    ON posts.image_id = images.id
                INNER JOIN post_reports
                    ON post_reports.post_id = posts.id";
        $reports = $this->executeQuery($sql);
        return $reports->fetchAll();
    }
    
    public function getSelectedReport($postId) {
        $sql = "SELECT posts.id, posts.name,  members.name as author, posts.content, images.filename
                FROM posts
                INNER JOIN members
                    ON posts.user_id = members.id
                INNER JOIN images
                    ON posts.image_id = images.id
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
    
    
    public function clearAllReports() {
        $sql = "DELETE FROM post_reports";
        $this->executeQuery($sql);
    }    
    
    public function clearReports($imgId) {
        $sql = "UPDATE images 
                SET reported = 0
                WHERE id = ?";
        $this->executeQuery($sql, array($imgId));
    }    
    
    public function deletePost($postId)
    {
        $sql = "DELETE images, posts 
                FROM images
                INNER JOIN posts
                    ON images.id = posts.image_id
                WHERE posts.id = ?";
        $this->executeQuery($sql, array($postId));
    }
    
    // Stats
    
    public function getLikesNbr()
    {
        $sql = "SELECT COUNT(id) as likesNbr FROM likes";
        $likesNbr = $this->executeQuery($sql);
        return $likesNbr->fetch();
    }
    
    public function getCommentsNbr()
    {
        $sql = "SELECT COUNT(id) as commentsNbr FROM comments";
        $commentsNbr = $this->executeQuery($sql);
        return $commentsNbr->fetch();
    }
    
    
    // Logs
    
    public function getLogs()
    {
        $sql = "SELECT logs.post_name as old_name, posts.name as new_name, posts.user_id as author, logs.post_content as old_content, posts.content as new_content
		        FROM logs 
                INNER JOIN posts 
                    ON logs.post_id = posts.id
                WHERE logs.mod_type = 'edited'
                ORDER BY logs.id DESC";
        $logs = $this->executeQuery($sql);
        return $logs->fetchAll();
    }
    
    
    public function insertPostLogs($postId){
        $sql ="INSERT INTO logs(posts.id, posts.name, posts.content)
			   SELECT posts.id, posts.name, posts.content, posts.user_id 
               FROM posts 
               WHERE post_id = ?";
        $this->executeQuery($sql, array($postId));
    }
    

}