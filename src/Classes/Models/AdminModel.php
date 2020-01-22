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
    
    public function getReportsComments()
    {
        $sql = "SELECT comments.id, author_id, content, com_date, members.name, avatars.avatar_file
                FROM comments
                INNER JOIN members
                    ON comments.author_id = members.id
                LEFT OUTER JOIN avatars
                    ON comments.author_id = avatars.user_id
                    AND avatars.active = 1
                WHERE reported > 0";
        $comReports = $this->executeQuery($sql);
        return $comReports->fetchAll();
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
    
    public function getMembers($limit, $offset)
    {
        $sql = "SELECT members.id, members.name, avatars.avatar_file, DATEDIFF(CURDATE(), members.date) as days_timestamp
                FROM members
                LEFT OUTER JOIN avatars
                    ON members.id = avatars.user_id
                    AND avatars.active = 1
                ORDER BY members.date ASC
                LIMIT :limit OFFSET :offset";
        $members = $this->executeLimitQuery($sql, $limit, $offset);
        return $members->fetchAll();
    }
    
    public function editPost($name, $content, $postId){
        $sql = "UPDATE posts
                SET name = ?, content = ?
                WHERE id= ?";
        $this->executeQuery($sql, array($name, $content, $postId));
    }
    
    
    public function clearAllReports() {
        $sql = "DELETE FROM post_reports";
        $this->executeQuery($sql);
    }
    
    public function clearCommentsReports()
    {
        $sql = "UPDATE comments SET reported = 0 WHERE reported > 0";
        $this->executeQuery($sql);
    }
    
    public function clearReport($postId)
    {
        $sql = "DELETE FROM post_reports
                WHERE post_id = ?";
        $this->executeQuery($sql, array($postId));
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