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
        $sql = "SELECT posts.id, posts.name, posts.content, images.filename, COUNT(post_reports.post_id) as nbReports
                FROM posts
                INNER JOIN images
                    ON posts.image_id = images.id
                INNER JOIN post_reports
                    ON post_reports.post_id = posts.id
                GROUP BY posts.id";
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
        $sql = "SELECT members.id, members.name, avatars.avatar_file, DATEDIFF(CURDATE(), members.date) as days_timestamp, 
                COUNT(distinct posts.id) as nbrPosts, COUNT(distinct comments.id) as nbrComments
                FROM members
                LEFT OUTER JOIN avatars
                    ON members.id = avatars.user_id
                    AND avatars.active = 1
                LEFT OUTER JOIN posts
                    ON members.id = posts.user_id
                LEFT OUTER JOIN comments
                    ON members.id = comments.author_id
                GROUP BY members.id
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
    
    public function clearAllCommentsReports()
    {
        $sql = "UPDATE comments SET reported = 0 WHERE reported > 0";
        $this->executeQuery($sql);
    }
    
    public function clearCommentReport($commentId)
    {
        $sql = "UPDATE comments SET reported = 0
                WHERE id = ?";
        $this->executeQuery($sql, array($commentId));
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
    
    public function deleteComment($commentId)
    {
        $sql = "DELETE FROM comments WHERE id = ?";
        $this->executeQuery($sql, array($commentId));
    }
    
    public function editComment($content, $commentId)
    {
        $sql = "UPDATE comments 
                SET content = ?, reported = 0, moderated = 1
                WHERE id = ?";
        $this->executeQuery($sql, array($content, $commentId));
    }
    
    public function clearComReport($commentId)
    {
        $sql = "UPDATE comments
                SET reported = 0, moderated = 1
                WHERE id = ?";
        $this->executeQuery($sql, array($commentId));
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
        $sql = "SELECT logs.post_name as old_name, posts.name as new_name, logs.author_id as author, 
                logs.post_content as old_content, posts.content as new_content, 
                logs.mod_type, DATE_FORMAT(post_date, '%d/%m/%Y') as date, images.filename, DATE_FORMAT(mod_date, '%d/%m/%Y') as mod_date
		        FROM logs 
                LEFT JOIN posts 
                    ON logs.post_id = posts.id
				LEFT JOIN images
                	ON posts.image_id = images.id
                ORDER BY logs.id DESC";
        $logs = $this->executeQuery($sql);
        return $logs->fetchAll();
    }
    
    
    public function getComLogs()
    {
        $sql = "SELECT logs_com.content as old_content, comments.content as new_content, logs_com.com_date, logs_com.author_id, logs_com.mod_type,
                members.name, avatars.avatar_file
                FROM logs_com
                INNER JOIN comments
                	ON logs_com.com_id = comments.id
                INNER JOIN members
                    ON logs_com.author_id = members.id
                LEFT OUTER JOIN avatars
                    ON logs_com.author_id = avatars.user_id
                    AND avatars.active = 1";
        $logsCom = $this->executeQuery($sql);
        return $logsCom->fetchAll();
    }
    
    public function insertPostLogs($modType, $postId){
        $sql ="INSERT INTO logs(post_id, post_name, post_content, author_id, post_date, mod_type, mod_date)
			   SELECT posts.id, posts.name, posts.content, posts.user_id, images.upload_date, ? as mod_type, NOW()
               FROM posts
               INNER JOIN images
               	ON posts.image_id = images.id
               WHERE posts.id = ?";
        $this->executeQuery($sql, array($modType, $postId));
    }
    
    public function insertComLogs($modType, $commentId)
    {
        $sql = "INSERT INTO logs_com (com_id, content, com_date, author_id, mod_type, mod_date)
                SELECT id, content, com_date, author_id, ? as mod_type, NOW()
                FROM comments
                WHERE id = ?";
        $this->executeQuery($sql, array($modType, $commentId));
    }
    
    public function clearLogsPosts()
    {
        $sql = "DELETE FROM logs";
        $this->executeQuery($sql);
    }
    
    public function clearLogsComments()
    {
        $sql = "DELETE FROM logs_com";
        $this->executeQuery($sql);
    }
    

}