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
        return $posts;
    }
    

}