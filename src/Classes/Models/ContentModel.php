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
    
    // Get informations for a single post
    public function getSelectedPost($postId)
    {
        $sql = "SELECT name, content, privacy
                FROM posts
                WHERE id = ?";
        $post = $this->executeQuery($sql, array($postId));
        return $post->fetch();
    }
    
    // Get comments from a marker from Map
    public function getCommentsNew($markerId)
    {
        $sql = "SELECT comments.id, members.name, members.avatar_file, comments.content, comments.com_date
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
    
    // Get comments refreshed
    public function getComments($postId)
    {
        $sql = "SELECT comments.id, members.name, members.avatar_file, comments.content, comments.com_date
                FROM comments 
                INNER JOIN members
	               ON comments.author_id = members.id
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