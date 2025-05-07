<?php
require_once __DIR__.'/BaseDao.php';

class AuthDao extends BaseDao {
    public function __construct() {
        parent::__construct('users'); 
    }

    public function get_user_by_email($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create_user($user) {
        $sql = "INSERT INTO users (email, password, username, created_at) 
                VALUES (:email, :password, :username, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'email' => $user['email'],
            'password' => $user['password'],
            'username' => $user['username'] ?? null
        ]);
        return $this->conn->lastInsertId();
    }

    public function update_user_token($user_id, $token) {
        $stmt = $this->conn->prepare("UPDATE users SET token = :token WHERE id = :id");
        return $stmt->execute([
            'id' => $user_id,
            'token' => $token
        ]);
    }

    public function get_user_by_token($token) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE token = :token");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}