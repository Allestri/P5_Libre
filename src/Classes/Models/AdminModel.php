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
        $sql = "SELECT * FROM images WHERE reported > 0";
        $reports = $this->executeQuery($sql);
        return $reports->fetchAll();
    }
    

}