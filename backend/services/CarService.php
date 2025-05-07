<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/CarDao.php';
require_once __DIR__ . '/../dao/ModelDao.php'; 
require_once __DIR__ . '/../dao/UserDao.php'; 

class CarService extends BaseService {
    public function __construct($dao) {
        parent::__construct($dao);
    }

    protected function validateCreateData($data) {
        if (empty($data['seller_id']) || empty($data['model_id']) || empty($data['price'])) {
            throw new Exception('Seller ID, model ID, and price are required');
        }
    }

    protected function validateUpdateData($data) {
        if ((isset($data['seller_id']) && empty($data['seller_id'])) ||
            (isset($data['model_id']) && empty($data['model_id'])) ||
            (isset($data['price']) && empty($data['price']))) {
            throw new Exception('Seller ID, model ID, and price cannot be empty');
        }
    }
}