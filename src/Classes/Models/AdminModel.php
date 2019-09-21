<?php 

namespace App\Models;

class AdminModel extends Model
{
    public function getReports(){
        $sql = "SELECT * FROM images WHERE reported > 1";
        $reports = $this->executeQuery($sql);
        return $reports->fetchAll();
    }
}