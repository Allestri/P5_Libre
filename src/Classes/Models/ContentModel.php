<?php

namespace App\Models;

Class ContentModel extends Model
{
     
    public function testContent()
    {
        $testDatas = "Hello world";
        return $testDatas;
    }
    
    public function getContent()
    {
        $sql = 'SELECT * FROM posts';
        $posts = $this->executeQuery($sql);
        return $posts->fetchAll();
    }
    
    public function addContent()
    {
        $sql = "INSERT INTO posts (name, content) VALUES (?, ?)";
        $this->executeQuery($sql, array($_POST['name'], $_POST['content']));
    }
    
    public function addPost($name, $description, $user, $imageId, $privacy){
        $sql = "INSERT INTO posts(name, content, user_id, image_id, marker_id, privacy) VALUES (?, ?, ?, ?, 2, ?)";
        $this->executeQuery($sql, array($name, $description, $user, $imageId, $privacy));
    }
    
    public function getComments($imgId)
    {
        $sql = "SELECT members.name, comments.content, comments.com_date
                FROM comments 
                INNER JOIN members
	               ON comments.author_id = members.id
                WHERE img_id = ?
                ORDER BY comments.id ASC";
        $comments = $this->executeQuery($sql, array($imgId));
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
        $sql = "SELECT members.name, 
                comments.content, comments.com_date,
                avatars.avatar_file
                FROM comments
                INNER JOIN members
                    ON comments.author_id = members.id
                INNER JOIN avatars
                    ON comments.author_id = avatars.user_id
                WHERE avatars.active = 1
                AND comments.img_id = 23";
         $comments = $this->executeQuery($sql);
         return $comments->fetchAll();
    }
    
    public function addComment($uid, $comment, $imgId)
    {
        $sql = "INSERT INTO comments (author_id, content, com_date, img_id) VALUES (?, ?, NOW(), ?)";
        $this->executeQuery($sql, array($uid, $comment, $imgId));
    }
    
    public function reportPost($postId) {
        $sql = "UPDATE posts
                SET reported = reported + 1
                WHERE id = ?";
        $this->executeQuery($sql, array($postId));
    }
    

}