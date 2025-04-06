<?php
require_once __DIR__ . "/BaseDao.php";

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct("users");
    }

    public function findByUsername($username) {
        return $this->query_unique("SELECT * FROM users WHERE username = :username", ["username" => $username]);
    }

    public function findByEmail($email) {
        return $this->query_unique("SELECT * FROM users WHERE email = :email", ["email" => $email]);
    }
    public function insert($entity) {
        return $this->add($entity);
    }
}
