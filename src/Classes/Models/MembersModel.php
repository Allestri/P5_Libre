<?php

namespace App\Models;

class MembersModel extends Model
{
    
    public function getAccountInfo($username)
    {        
        $sql = "SELECT * FROM members WHERE name = ?";
        $member = $this->executeQuery($sql, array($username));
        return $member->fetch();
    }
    
    public function checkMemberUnique($username)
    {
        $member = $this->getAccountInfo($username);
        if($member != false){
            return false;
        }
        return true;
    }
    
    public function countMembers($username)
    {
        $sql ="SELECT count(*) FROM members WHERE name = ?";
        $members = $this->executeQuery($sql, array($username));
        return $members->fetch();
    }
    
    public function signup($username, $rdp)
    {
        $sql = "INSERT INTO members (name, password, date) VALUES (?, ?, NOW())";
        $this->executeQuery($sql, array($username, $rdp));
    }
}