<?php
require_once __DIR__.'/BaseService.php';
require_once __DIR__.'/../dao/ModelDao.php';

class ModelService extends BaseService {
    public function __construct() {
        parent::__construct(new ModelDao());
    }

    public function getModelsByBrand($brand_id) {
        return $this->dao->getModelsByBrand($brand_id);
    }

    public function create($data) {
        if (empty($data['model_name']) || empty($data['brand_id'])) {
            throw new Exception("Model name and brand ID are required");
        }
        return parent::create($data);
    }
}