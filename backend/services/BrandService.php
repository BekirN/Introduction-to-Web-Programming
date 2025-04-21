<?php
require_once __DIR__.'/BaseService.php';
require_once __DIR__.'/../dao/BrandDao.php';

class BrandService extends BaseService {
    public function __construct() {
        parent::__construct(new BrandDao());
    }

    protected function validateCreateData($data) {
        // Validate data when creating a new brand
        if (empty($data['brand_name'])) {
            throw new Exception("Brand name is required");
        }
        
        if (strlen($data['brand_name']) > 50) {
            throw new Exception("Brand name must be 50 characters or less");
        }
    }

    protected function validateUpdateData($data) {
        // Validate data when updating a brand
        if (isset($data['brand_name']) && empty($data['brand_name'])) {
            throw new Exception("Brand name cannot be empty");
        }
        
        if (isset($data['brand_name']) && strlen($data['brand_name']) > 50) {
            throw new Exception("Brand name must be 50 characters or less");
        }
    }

    // Additional brand-specific methods can go here
    public function getBrandWithModels($brand_id) {
        $brand = $this->dao->getById($brand_id);
        $brand['models'] = $this->dao->getModelsByBrand($brand_id);
        return $brand;
    }
}