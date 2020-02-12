<?php

namespace App\Models;

class MembersModel extends Model
{   
    public function getAccountInfo($username)
    {        
        $sql = "SELECT members.id, members.password, members.group_id, avatars.avatar_file  
                FROM members
                LEFT OUTER JOIN avatars
                	ON members.id = avatars.user_id
                    AND avatars.active = 1
                WHERE members.name = ?";
        $member = $this->executeQuery($sql, array($username));
        return $member->fetch();
    }
    
    // Get the list of users without the connected member
    public function getMembersList($username)
    {
        $sql = "SELECT id, name, avatar_file
                FROM members
                WHERE NOT name = ?";
        $members = $this->executeQuery($sql, array($username));
        return $members->fetchAll();
    }
    
    public function getFullMembersList()
    {
        $sql = "SELECT id, name, avatar_file, DATE_FORMAT(date, '%d/%m/%Y à %Hh%imin%ss') as date_fr
                FROM members";
        $members = $this->executeQuery($sql);
        return $members->fetchAll();
    }
    
    // Used in memberlist page with pagination
    public function getAllMembersLimit($limit, $offset, $userId)
    {
        $sql = "SELECT members.id, members.name, avatars.avatar_file, DATEDIFF(CURDATE(), members.date) as days_timestamp
                FROM members
                LEFT OUTER JOIN avatars
                    ON members.id = avatars.user_id
                    AND avatars.active = 1
                WHERE members.id != :param
                ORDER BY members.date DESC
                LIMIT :limit OFFSET :offset";
        $members = $this->executeLimitQuery($sql, $limit, $offset, $userId);
        return $members->fetchAll();
    }
    
    // Used in memberlist page with pagination
    public function getMembersSortName($limit, $offset, $userId)
    {
        $sql = "SELECT members.id, members.name, avatars.avatar_file, DATEDIFF(CURDATE(), members.date) as days_timestamp
                FROM members
                LEFT OUTER JOIN avatars
                    ON members.id = avatars.user_id
                    AND avatars.active = 1
                WHERE members.id != :param
                ORDER BY members.name ASC
                LIMIT :limit OFFSET :offset";
        $members = $this->executeLimitQuery($sql, $limit, $offset, $userId);
        return $members->fetchAll();
    }
    
    // Not using.
    public function getMembersOrderFriends($limit, $offset, $userId)
    {
        $sql = "SELECT members.id, members.name, avatars.avatar_file, DATEDIFF(CURDATE(), members.date) as days_timestamp
                FROM members
                LEFT OUTER JOIN avatars
                    ON members.id = avatars.user_id
                    AND avatars.active = 1
                RIGHT JOIN friendship
					ON friend_a = members.id
                    OR friend_b = members.id
                WHERE members.id != 6
                LIMIT :limit OFFSET :offset";
        $members = $this->executeLimitQuery($sql, $limit, $offset, $userId);
        return $members->fetchAll();
    }
    
    
    public function getFriendships($uid)
    {
        $sql = "SELECT * FROM friendship WHERE friend_a = :uid OR friend_b = :uid";
        $friendships = $this->executeQuery($sql, array(':uid' => $uid));
        return $friendships->fetchAll();
    }
    
    // Count members without connected user
    public function countMembers($username)
    {
        $sql ="SELECT count(*) FROM members WHERE name = ?";
        $members = $this->executeQuery($sql, array($username));
        return $members->fetch();
    }
    
    
    // Count every members registered
    public function countAllMembers()
    {
        $sql = "SELECT count(*) as totalmembers FROM members";
        $members = $this->executeQuery($sql);
        return $members->fetch();
    }
    
    public function checkMemberUnique($username)
    {
        $member = $this->getAccountInfo($username);
        if($member != false){
            return false;
        }
        return true;
    }
       
    public function signup($username, $rdp, $ip)
    {
        $sql = "INSERT INTO members (name, password, ip_address, group_id, date) VALUES (?, ?, ?, 3, NOW())";
        $this->executeQuery($sql, array($username, $rdp, $ip));
    }
    
    // Friendship System
    // @param : $uid : connected user unique id
    //          $fid : receiver/friend unique id
    
    public function addFriendRequest($uid, $fid)
    {
        $sql = "INSERT INTO friendship (friend_a, friend_b, status, friend_date) VALUES (?, ?, 'pending', NOW())";
        $this->executeQuery($sql, array($uid, $fid));
    }
    
    public function cancelFriendRequest($uid, $fid)
    {
        $sql = "DELETE FROM friendship 
                WHERE friend_a = ? AND friend_b = ?";
        $this->executeQuery($sql, array($uid, $fid));
    }
    
    public function addFriendAccept($fid, $uid)
    {
        $sql ="UPDATE friendship 
               SET status='friend' 
               WHERE friend_a = ? AND friend_b = ?";
        $this->executeQuery($sql, array($fid, $uid));
    }
    
    public function ignoreFriendRequest($fid, $uid)
    {
        $sql = "UPDATE friendship
                SET ignored = 1
                WHERE friend_a = ? AND friend_b = ?";
        $this->executeQuery($sql, array($fid, $uid));
    }
    
    public function removeFriend($fid, $uid)
    {
        $sql = "DELETE FROM friendship
                WHERE (friend_a = :from AND friend_b = :to) OR (friend_a = :to AND friend_b = :from)";
        $this->executeQuery($sql, array(':from' => $uid, ':to' => $fid));
    }
    
    public function getFriendRequests($uid)
    {
        $sql = "SELECT friendship.friend_a, members.name
                FROM friendship
                INNER JOIN members 
                    ON friendship.friend_a = members.id
                WHERE friend_b = ? AND status = 'pending' AND ignored = 0";
        $fReq = $this->executeQuery($sql, array($uid));
        return $fReq->fetchAll();
    }
    
    public function getFriends($uid)
    {
        $sql = "SELECT members.id, members.name, avatars.avatar_file
                FROM members
                INNER JOIN friendship
                    ON members.id = friendship.friend_b
                LEFT OUTER JOIN avatars
                    ON members.id = avatars.user_id
                    AND avatars.active = 1
                WHERE (friendship.friend_a = :uid OR friendship.friend_b = :uid) AND status='friend'";
        $friends = $this->executeQuery($sql, array(':uid' => $uid));
        return $friends->fetchAll();
    }
    
    // Avatars
    
    public function getAvatar($uid)
    {
        $sql = "SELECT avatar_file FROM members WHERE id = ?";
        $avatar = $this->executeQuery($sql, array($uid));
        return $avatar->fetch();
    }
    
    public function getAvatarNew($uid)
    {
        $sql = "SELECT avatar_file FROM avatars WHERE user_id = ? AND active = 1";
        $avatar = $this->executeQuery($sql, array($uid));
        return $avatar->fetch();
    }
    
    public function getAvatars($uid)
    {
        $sql = "SELECT id, avatar_file 
                FROM avatars 
                WHERE user_id = ? 
                ORDER BY active DESC";
        $avatars = $this->executeQuery($sql, array($uid));
        return $avatars->fetchAll();
    }
    
    public function changeAvatar($filename, $uid)
    {
        $sql = "UPDATE members SET avatar_file = ? WHERE id = ?";
        $this->executeQuery($sql, array($filename, $uid));
    }
    
    public function unactiveAvatars($uid)
    {
        $sql = "UPDATE avatars SET active = CASE
                WHEN 1 THEN 0
                ELSE active
                END
                WHERE user_id = ?";
        $this->executeQuery($sql, array($uid));
    }
    
    public function switchAvatar($avatarId, $uid)
    {
        $sql = "UPDATE avatars SET active = 1
                WHERE id = ?
                AND user_id = ?";
        $this->executeQuery($sql, array($avatarId, $uid));
    }
    
    public function hasCustomAvatar()
    {
        $sql = "UPDATE members SET custom_avatar = 1";
        $this->executeQuery($sql);
    }
    public function changeAvatarNew($uid, $filename)
    {
        $sql = "INSERT INTO avatars (user_id, avatar_file, active) VALUES (?, ?, 1)";
        $this->executeQuery($sql, array($uid, $filename));
    }
    
    public function deleteAvatar($avatarId)
    {
        $sql = "DELETE FROM avatars
                WHERE id = ?";
        $this->executeQuery($sql, array($avatarId));
    }
    
    
    // Settings 
    
    public function setEmail($email, $uid)
    {
        $sql = "UPDATE members SET email = ?
                WHERE id = ?";
        $this->executeQuery($sql, array($email, $uid));
    }
    
    public function changePassword($password, $uid)
    {
        $sql = "UPDATE members SET password = ?
                WHERE id = ?";
        $this->executeQuery($sql, array($password, $uid));
    }
    
    public function getRecentPhotos($uid)
    {
        $sql = "SELECT filename FROM images WHERE user_id = ? LIMIT 0,4";
        $recentImgs = $this->executeQuery($sql, array($uid));
        return $recentImgs->fetchAll();
    }
    
}