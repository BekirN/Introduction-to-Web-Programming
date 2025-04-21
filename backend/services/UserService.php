<?php
require_once __DIR__.'/BaseService.php';
require_once __DIR__.'/../dao/UserDao.php';

class UserService extends BaseService {
    public function __construct() {
        parent::__construct(new UserDao());
    }

    public function register($user) {
        if (empty($user['email']) || empty($user['password'])) {
            throw new Exception("Email and password are required");
        }
        
        $user['password_hash'] = password_hash($user['password'], PASSWORD_BCRYPT);
        unset($user['password']);
        
        return $this->dao->create($user);
    }

    public function login($email, $password) {
        $user = $this->dao->getUserByEmail($email);
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new Exception("Invalid login credentials");
        }
        
        return $user;
    }

    public function getSellerCars($user_id) {
        return $this->dao->getSellerCars($user_id);
    }
}