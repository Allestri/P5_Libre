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
    
    // Get the list of without the connected member
    public function getMembersList($username)
    {
        $sql = "SELECT id, name FROM members
                WHERE NOT name = ?";
        $members = $this->executeQuery($sql, array($username));
        return $members->fetchAll();
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
    
    public function signup($username, $rdp, $ip)
    {
        $sql = "INSERT INTO members (name, password, ip_address, date) VALUES (?, ?, ?, NOW())";
        $this->executeQuery($sql, array($username, $rdp, $ip));
    }
    
    // Friendship System
    
    public function addFriendRequest($uid, $fid)
    {
        $sql = "INSERT INTO friend_requests (uid, fid, created_at) VALUES (?, ?, NOW())";
        $this->executeQuery($sql, array($uid, $fid));
    }
    
    public function clearFriendRequest()
    {
        $sql = "DELETE FROM friend_requests
                WHERE uid = ? AND fid = ?";
        $this->executeQuery($sql, array($uid, $fid));
    }
    
    public function addFriendAccept()
    {
        $sql ="INSERT INTO friendship (uid, fid) VALUES (?, ?)";
        $this->executeQuery($sql, array($uid, $fid));
    }
    
    public function getFriendRequests($uid)
    {
        $sql = "SELECT friend_requests.uid, members.name
                FROM friend_requests
                INNER JOIN members 
                    ON friend_requests.uid = members.id
                WHERE fid = ?";
        $fReq = $this->executeQuery($sql, array($uid));
        return $fReq->fetchAll();
    }
    
}