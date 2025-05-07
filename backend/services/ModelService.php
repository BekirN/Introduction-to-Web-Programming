<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/ModelDao.php';
require_once __DIR__ . '/../dao/BrandDao.php';

class ModelService extends BaseService {
    public function __construct($dao) {
        parent::__construct($dao);
    }

    protected function validateCreateData($data) {
        if (empty($data['brand_id']) || empty($data['model_name']) || empty($data['year'])) {
            throw new Exception('Brand ID, model name, and year are required');
        }
    }

    protected function validateUpdateData($data) {
        if ((isset($data['brand_id']) && empty($data['brand_id'])) ||
            (isset($data['model_name']) && empty($data['model_name'])) ||
            (isset($data['year']) && empty($data['year']))) {
            throw new Exception('Brand ID, model name, and year cannot be empty');
        }
    }
}