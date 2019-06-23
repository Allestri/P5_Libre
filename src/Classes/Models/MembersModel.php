<?php

namespace App\Models;

class MembersModel extends Model
{
    
    public function getAccountInfo()
    {
        $sql = "SELECT * FROM members WHERE name = ?";
        $member = $this->executeQuery($sql, array($_POST['uid']));
        return $member->fetch();
    }
    
    public function countMembers()
    {
        $sql ="SELECT count(*) FROM members WHERE name = ?";
        $members = $this->executeQuery($sql, array($_POST['uid']));
        return $members;
    }
    
    public function signup($args)
    {
        $sql = "INSERT INTO members (name, password, date) VALUES (?, ?, NOW())";
        $this->executeQuery($sql, array($_POST['uid'], $args));
    }
}