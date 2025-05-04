<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/TransactionDao.php';
require_once __DIR__ . '/../dao/CarDao.php'; 
require_once __DIR__ . '/../dao/UserDao.php';

class TransactionService extends BaseService {
    public function __construct($dao) {
        parent::__construct($dao);
    }

    protected function validateCreateData($data) {
        if (empty($data['car_id']) || empty($data['buyer_id']) || empty($data['seller_id']) || empty($data['sale_price'])) {
            throw new Exception('Car ID, buyer ID, seller ID, and sale price are required');
        }
    }

    protected function validateUpdateData($data) {
        if ((isset($data['car_id']) && empty($data['car_id'])) ||
            (isset($data['buyer_id']) && empty($data['buyer_id'])) ||
            (isset($data['seller_id']) && empty($data['seller_id'])) ||
            (isset($data['sale_price']) && empty($data['sale_price']))) {
            throw new Exception('Car ID, buyer ID, seller ID, and sale price cannot be empty');
        }
    }
}