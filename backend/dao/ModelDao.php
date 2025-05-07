<?php
require_once __DIR__ . '/BaseDao.php';

class ModelDao extends BaseDao {
    public function __construct() {
        parent::__construct('models', 'model_id');
    }

    public function findByBrandId($brand_id) {
        $query = "SELECT * FROM {$this->table_name} WHERE brand_id = :brand_id";
        return $this->query($query, ['brand_id' => $brand_id]);
    }

    public function getById($id, $id_column = 'model_id') {
        return parent::getById($id, $id_column);
    }

    public function update($id, $entity, $id_column = 'model_id') {
        return parent::update($id, $entity, $id_column);
    }

    public function delete($id, $id_column = 'model_id') {
        return parent::delete($id, $id_column);
    }
}