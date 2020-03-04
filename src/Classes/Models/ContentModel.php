<?php

namespace App\Models;

Class ContentModel extends Model
{
     
    public function testContent()
    {
        $testDatas = "Hello world";
        return $testDatas;
    }
    
    public function getRecentPosts()
    {
        $sql = "SELECT posts.id, posts.name, posts.content, members.name as author, images.filename, images.upload_date, 
                COUNT(distinct likes.id) as likes, COUNT(distinct comments.id) as comNbr
                FROM posts
                INNER JOIN images
                	ON posts.image_id = images.id
                INNER JOIN members
                	ON posts.user_id = members.id
                LEFT JOIN likes
                    ON posts.id = likes.post_id
                LEFT JOIN comments
                	ON posts.id = comments.post_id
				WHERE posts.privacy = 0
				GROUP BY posts.id
                ORDER BY upload_date DESC
                LIMIT 0,3";
        $recentPosts = $this->executeQuery($sql);
        return $recentPosts->fetchAll();
    }
    
    public function getContent($limit, $offset)
    {
        $sql = 'SELECT * FROM placeholder LIMIT :limit OFFSET :offset';
        $posts = $this->executeQueryNew($sql, null, $limit, $offset);
        return $posts->fetchAll();
    }
    
    public function addContent()
    {
        $sql = "INSERT INTO posts (name, content) VALUES (?, ?)";
        $this->executeQuery($sql, array($_POST['name'], $_POST['content']));
    }
    
    public function countContent()
    {
        $sql = "SELECT COUNT(*) as rows FROM placeholder";
        $contentNbr = $this->executeQuery($sql);
        return $contentNbr->fetch();
    }
    
    public function addPost($name, $description, $user, $imageId, $markerId, $privacy){
        $sql = "INSERT INTO posts(name, content, user_id, image_id, marker_id, privacy) VALUES (?, ?, ?, ?, ?, ?)";
        $this->executeQuery($sql, array($name, $description, $user, $imageId, $markerId, $privacy));
    }
    
    // Get informations for a single post
    public function getSelectedPost($postId)
    {
        $sql = "SELECT name, content, privacy
                FROM posts
                WHERE id = ?";
        $post = $this->executeQuery($sql, array($postId));
        return $post->fetch();
    }
    
    public function getDate()
    {
        $sql = "SELECT UNIX_TIMESTAMP(com_date) as timestamp FROM comments WHERE content ='Jolie vue !'";
        $dateTime = $this->executeQuery($sql);
        return $dateTime->fetch();
    }
    
    // Get comments from a marker from Map
    public function getCommentsNew($markerId)
    {
        $sql = "SELECT comments.id, members.name, avatars.avatar_file, comments.content, UNIX_TIMESTAMP(comments.com_date) as com_date
                FROM comments
                INNER JOIN members
                    ON comments.author_id = members.id
                LEFT OUTER JOIN avatars
                    ON comments.author_id = avatars.user_id
                    AND avatars.active = 1
                INNER JOIN posts
                	ON comments.post_id = posts.id
                WHERE posts.marker_id = ?
                ORDER BY comments.id ASC";
        $comments = $this->executeQuery($sql, array($markerId));
        return $comments->fetchAll();
    }
    
    // Get comments refreshed
    public function getComments($postId)
    {
        $sql = "SELECT comments.id, members.name, avatars.avatar_file, comments.content, UNIX_TIMESTAMP(comments.com_date) as com_date
                FROM comments 
                INNER JOIN members
	               ON comments.author_id = members.id
                LEFT OUTER JOIN avatars
                    ON comments.author_id = avatars.user_id
                    AND avatars.active = 1
                WHERE post_id = ?
                ORDER BY comments.id ASC";
        $comments = $this->executeQuery($sql, array($postId));
        return $comments->fetchAll();
    }
    
    // Gets my comments - used in Profile Page.
    public function getMyComments($uid)
    {
        $sql = "SELECT * FROM comments
                WHERE author_id = ?";
        $myComments = $this->executeQuery($sql, array($uid));
        return $myComments->fetchAll();
    }
    
    public function getPostId($filename)
    {
        $sql = "SELECT posts.id
                FROM posts
                INNER JOIN images
                    ON images.id = posts.image_id
                WHERE images.filename = ?";
        $postId = $this->executeQuery($sql, array($filename));
        return $postId->fetch();
    }
    
    public function getIds($filename)
    {
        $sql = "SELECT posts.id as post_id, images.id as image_id
                FROM posts
	            INNER JOIN images
    	           ON posts.image_id = images.id
                WHERE images.filename = ?";
        $ids = $this->executeQuery($sql, array($filename));
        return $ids->fetch();
    }
    

    
    // CRUD 
    
    public function deletePost($postId, $uid)
    {
        $sql = "DELETE FROM posts
                WHERE id = ?
                AND user_id = ?";
        $this->executeQuery($sql, array($postId, $uid));
    }
    
    public function editPost($name, $content, $privacy, $postId)
    {
        $sql =  "UPDATE posts
                SET name = ?, content = ?, privacy = ?
                WHERE id= ?";
        $this->executeQuery($sql, array($name, $content, $privacy, $postId));
    }
    
    
    // Social 
    
    public function addComment($uid, $comment, $postId)
    {
        $sql = "INSERT INTO comments (author_id, content, com_date, post_id) VALUES (?, ?, NOW(), ?)";
        $this->executeQuery($sql, array($uid, $comment, $postId));
    }
    
    public function deleteComment($uid, $commentId)
    {
        $sql = "DELETE FROM comments
                WHERE author_id = ? AND id = ?";
        $this->executeQuery($sql, array($uid, $commentId));
    }
    
    public function reportPost($userId, $postId) {
        $sql = "INSERT INTO post_reports (user_id, post_id) VALUES(?, ?)";
        $this->executeQuery($sql, array($userId, $postId));
    }
    
    public function reportComment($commentId)
    {
        $sql ="UPDATE comments SET reported = reported + 1 WHERE id = ?";
        $this->executeQuery($sql,array($commentId));
    }
    
    public function likePost($userId, $postId)
    {
        $sql = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
        $this->executeQuery($sql, array($userId, $postId));
    }
    
    public function unlikePost($userId, $postId)
    {
        $sql = "DELETE FROM likes
                WHERE user_id = ? AND post_id = ?";
        $this->executeQuery($sql, array($userId, $postId));
    }
    
    public function getLikesNew($postId)
    {
        $sql = "SELECT count(likes.id) as likes
                FROM likes
                WHERE post_id = ?";
        $likes = $this->executeQuery($sql, array($postId));
        return $likes->fetch();
    }
    
    public function getMyLikes($markerId, $userId)
    {
        $sql = "SELECT count(likes.id) as uliked
                FROM likes
                INNER JOIN posts
                    ON likes.post_id = posts.id
                WHERE posts.marker_id = ? AND likes.user_id = ?";
        $liked = $this->executeQuery($sql, array($markerId, $userId));
        return $liked->fetch();
    }
    
    public function getLikes($markerId)
    {
        $sql = "SELECT count(likes.id) as likes
                FROM likes
                INNER JOIN posts
	               ON likes.post_id = posts.id
                WHERE posts.marker_id = ?";
        $likes = $this->executeQuery($sql, array($markerId));
        return $likes->fetch();
    }
            

}