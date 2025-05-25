<?php
require_once 'BaseDao.php';

class UserDao extends BaseDao {
    public function __construct()
    {
        parent::__construct("users");
    }

    public function get_user_by_username($username) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function get_all_sellers() {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE is_seller = 1");
        $stmt->execute();
        return $stmt->fetchAll();
    }

}
