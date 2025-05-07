<?php
require_once __DIR__.'/BaseService.php';
require_once __DIR__.'/../dao/AuthDao.php'; 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService {
    private $auth_dao;
    
    public function __construct() {
        $this->auth_dao = new AuthDao();
        parent::__construct($this->auth_dao);
    }

    protected function validateCreateData($data) {
        if (empty($data['email'])) {
            throw new Exception("Email is required");
        }
        if (empty($data['password'])) {
            throw new Exception("Password is required");
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        return $data;
    }

    protected function validateUpdateData($data) {
        return $data; 
    }

    public function get_user_by_email($email) {
        return $this->auth_dao->get_user_by_email($email);
    }

    public function register($entity) {   
        try {
            $entity = $this->validateCreateData($entity);
            
            if ($this->get_user_by_email($entity['email'])) {
                throw new Exception("Email already registered");
            }

            $entity['password'] = password_hash($entity['password'], PASSWORD_BCRYPT);
            $result = parent::add($entity);
            unset($result['password']);
            
            return $result;
        } catch (Exception $e) {
            throw $e;
        }          
    }

    public function login($entity) {   
        try {
            if (empty($entity['email']) || empty($entity['password'])) {
                throw new Exception("Email and password are required");
            }

            $user = $this->get_user_by_email($entity['email']);
            if (!$user || !password_verify($entity['password'], $user['password'])) {
                throw new Exception("Invalid credentials");
            }

            unset($user['password']);
            
            $jwt_payload = [
                'user' => $user,
                'iat' => time(),
                'exp' => time() + (60 * 60 * 24) // 1 day expiration
            ];

            $token = JWT::encode(
                $jwt_payload,
                Config::JWT_SECRET(),
                'HS256'
            );

            return array_merge($user, ['token' => $token]);
        } catch (Exception $e) {
            throw $e;
        }             
    }
}