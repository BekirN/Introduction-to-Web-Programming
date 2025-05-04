<?php
require_once 'BaseService.php';

class BrandService extends BaseService {
    public function __construct($dao) {
        parent::__construct($dao);
    }

    protected function validateCreateData($data) {
        if (empty($data['brand_name'])) {
            throw new Exception('Brand name is required');
        }
    }

    protected function validateUpdateData($data) {
        if (isset($data['brand_name']) && empty($data['brand_name'])) {
            throw new Exception('Brand name cannot be empty');
        }
    }
}