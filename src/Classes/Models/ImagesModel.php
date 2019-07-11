<?php

namespace App\Models;

Class ImagesModel extends Model
{
    
    public function testContent()
    {
        $testDatas = "Hello world";
        return $testDatas;
    }
      
    
    // NOT FINISHED
    
    public function addDatas($expr, $lng, $lat)
    {
        $sql = "INSERT INTO photos (name, address, lng, lat, upload_date, type) VALUES(?, 'placeholder', ?, ?, NOW(), 'jpeg')";
        $title = $_POST['titre'];
        $this->executeQuery($sql, array($title, $expr, $lng, $lat));
    }
    
}