<?php

namespace App\Models;

class MembersModel extends Model
{
    
    public function getAccountInfo()
    {
        $sql = "SELECT * FROM members WHERE name = ?";
        $member = $this->executeQuery($sql, array($_POST['uid']));
        return $member;
    }
    
    public function signup()
    {
        $sql = "INSERT INTO members (name, password, date) VALUES (?, ?, NOW())";
        $this->executeQuery($sql, array($_POST['uid'], $_POST['pwd']));
    }
}