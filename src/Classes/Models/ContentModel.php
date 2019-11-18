<?php

namespace App\Models;

Class ContentModel extends Model
{
     
    public function testContent()
    {
        $testDatas = "Hello world";
        return $testDatas;
    }
    
    public function getContent($limit, $offset)
    {
        $sql = 'SELECT * FROM placeholder LIMIT :limit OFFSET :offset';
        $posts = $this->executeLimitQuery($sql, $limit, $offset);
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
    
    public function getComments($postId)
    {
        $sql = "SELECT members.name, comments.content, comments.com_date
                FROM comments 
                INNER JOIN members
	               ON comments.author_id = members.id
                WHERE post_id = ?
                ORDER BY comments.id ASC";
        $comments = $this->executeQuery($sql, array($postId));
        return $comments->fetchAll();
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
    
    public function getCommentsNew($markerId)
    {
        $sql = "SELECT members.name, comments.content, comments.com_date
                FROM comments
                INNER JOIN members
                    ON comments.author_id = members.id
                INNER JOIN posts
                	ON comments.post_id = posts.id
                WHERE posts.marker_id = ?
                ORDER BY comments.id ASC";
         $comments = $this->executeQuery($sql, array($markerId));
         return $comments->fetchAll();
    }
    
    
    // Social 
    
    public function addComment($uid, $comment, $postId)
    {
        $sql = "INSERT INTO comments (author_id, content, com_date, post_id) VALUES (?, ?, NOW(), ?)";
        $this->executeQuery($sql, array($uid, $comment, $postId));
    }
    
    public function reportPost($postId) {
        $sql = "UPDATE posts 
                SET reported = reported + 1
                WHERE id = ?";
        $this->executeQuery($sql, array($postId));
    }
    
    public function likePost($postId){
        $sql = "UPDATE posts
                SET liked = liked + 1
                WHERE id = ?";
        $this->executeQuery($sql, array($postId));
    }
    

}