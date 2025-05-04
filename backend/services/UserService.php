<?php
require_once 'BaseService.php';

class UserService extends BaseService {
    public function __construct($dao) {
        parent::__construct($dao);
    }

    protected function validateCreateData($data) {
        if (empty($data['username']) || empty($data['email']) || empty($data['password_hash'])) {
            throw new Exception('Username, email, and password hash are required');
        }
    }

    protected function validateUpdateData($data) {
        if ((isset($data['username']) && empty($data['username'])) ||
            (isset($data['email']) && empty($data['email'])) ||
            (isset($data['password_hash']) && empty($data['password_hash']))) {
            throw new Exception('Username, email, and password hash cannot be empty');
        }
    }
}