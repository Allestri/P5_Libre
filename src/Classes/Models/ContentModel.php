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
    
    public function getComments()
    {
        $sql = "SELECT author_id, content FROM comments WHERE img_id = 23";
        $comments = $this->executeQuery($sql);
        return $comments->fetchAll();
    }
    
    public function addComment($uid, $comment, $imgId)
    {
        $sql = "INSERT INTO comments (author_id, content, date, img_id) VALUES (?, ?, NOW(), ?)";
        $this->executeQuery($sql, array($uid, $comment, $imgId));
    }
    

}