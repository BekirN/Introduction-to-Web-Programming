<?php
require_once __DIR__ . '/BaseDao.php';

class BrandDao extends BaseDao {
    public function __construct() {
        parent::__construct('brands', 'brand_id');
    }

    public function findByName($brand_name) {
        $query = "SELECT * FROM {$this->table_name} WHERE brand_name = :brand_name";
        return $this->query_unique($query, ['brand_name' => $brand_name]);
    }

    public function getById($id, $id_column = 'brand_id') {
        return parent::getById($id, $id_column);
    }

    public function update($id, $entity, $id_column = 'brand_id') {
        return parent::update($id, $entity, $id_column);
    }

    public function delete($id, $id_column = 'brand_id') {
        return parent::delete($id, $id_column);
    }
    
}