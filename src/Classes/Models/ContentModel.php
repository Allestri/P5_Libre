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
    
    public function addComment($uid, $comment, $imgId)
    {
        $sql = "INSERT INTO comments (author_id, content, com_date, img_id) VALUES (?, ?, NOW(), ?)";
        $this->executeQuery($sql, array($uid, $comment, $imgId));
    }
    

}