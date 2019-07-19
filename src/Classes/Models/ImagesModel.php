<?php

namespace App\Models;

Class ImagesModel extends Model
{
    
    public function testContent()
    {
        $testDatas = "Hello world";
        return $testDatas;
    }
      
    
    public function fetchMarkersM()
    {
        $sql = "SELECT * FROM markers WHERE 1";
        $markers = $this->executeQuery($sql);
        return $markers->fetchAll();
    }
        
    
    public function addDatas($lng, $lat, $alt)
    {
        $sql = "INSERT INTO markers (name, address, lng, lat, altitude, upload_date, type) VALUES(?, 'placeholder', ?, ?, ?, NOW(), 'jpeg')";
        // Title goes to controller soon.
        $title = $_POST['name'];
        $this->executeQuery($sql, array($title, $lng, $lat, $alt));
    }
    
}