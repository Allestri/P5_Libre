<?php 

namespace App\Models;

class AdminModel extends Model
{
    
    public function countReports(){
        $sql = "SELECT COUNT(id) as reportsNbr FROM images WHERE reported > 0";
        $reportsNbr = $this->executeQuery($sql);
        return $reportsNbr->fetch();
    }
    
    public function getReports(){
        $sql = "SELECT images.id, images.filename, images.liked, images.reported, markers.name
                FROM images
                INNER JOIN markers
                    ON images.id = markers.image_id
                WHERE reported > 0";
        $reports = $this->executeQuery($sql);
        return $reports->fetchAll();
    }
    

}