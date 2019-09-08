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
        $sql = "INSERT INTO members (name, password, avatar_file, ip_address, group_id, date) VALUES (?, ?, 'avatar_default.png', ?, 3, NOW())";
        $this->executeQuery($sql, array($username, $rdp, $ip));
    }
    
    // Friendship System
    
    public function addFriendRequest($uid, $fid)
    {
        $sql = "INSERT INTO friend_requests (sender_id, receiver_id, created_at) VALUES (?, ?, NOW())";
        $this->executeQuery($sql, array($uid, $fid));
    }
    
    public function clearFriendRequest($fid, $uid)
    {
        $sql = "DELETE FROM friend_requests
                WHERE sender_id = ? AND receiver_id = ?";
        $this->executeQuery($sql, array($fid, $uid));
    }
    
    public function addFriendAccept($fid, $uid)
    {
        $sql ="INSERT INTO friendship (friend_a, friend_b, friend_date) VALUES (?, ?, NOW())";
        $this->executeQuery($sql, array($fid, $uid));
    }
    
    public function getFriendRequests($uid)
    {
        $sql = "SELECT friend_requests.sender_id, members.name
                FROM friend_requests
                INNER JOIN members 
                    ON friend_requests.sender_id = members.id
                WHERE receiver_id = ?";
        $fReq = $this->executeQuery($sql, array($uid));
        return $fReq->fetchAll();
    }
    
    public function getFriends($uid)
    {
        $sql = "SELECT members.id, members.name, members.avatar_file
                FROM members
                INNER JOIN friendship
                    ON members.id = friendship.friend_b
                WHERE friendship.friend_a = ?";
        $friends = $this->executeQuery($sql, array($uid));
        return $friends->fetchAll();
    }
    
    public function getAvatar($uid)
    {
        $sql = "SELECT avatar_file FROM members WHERE id = ?";
        $avatar = $this->executeQuery($sql, array($uid));
        return $avatar->fetch();
    }
    
    public function changeAvatar($filename, $uid)
    {
        $sql = "UPDATE members SET avatar_file = ? WHERE id = ?";
        $this->executeQuery($sql, array($filename, $uid));
    }
    
    public function getRecentPhotos($uid)
    {
        $sql = "SELECT filename FROM images WHERE user_id = ? LIMIT 0,4";
        $recentImgs = $this->executeQuery($sql, array($uid));
        return $recentImgs->fetchAll();
    }
}