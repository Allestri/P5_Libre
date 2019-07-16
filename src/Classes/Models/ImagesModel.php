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
    
    public function addDatas($lng, $lat)
    {
        $sql = "INSERT INTO photos (name, address, lng, lat, altitude, upload_date, type) VALUES(?, 'placeholder', ?, ?, 5, NOW(), 'jpeg')";
        // Title goes to controller soon.
        $title = $_POST['name'];
        $this->executeQuery($sql, array($title, $lng, $lat));
    }
    
}