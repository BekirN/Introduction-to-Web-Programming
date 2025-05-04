<?php
require_once __DIR__ . '/BaseDao.php';

class CarDao extends BaseDao {
    public function __construct() {
        parent::__construct('cars', 'car_id');
    }

    public function findBySellerId($seller_id) {
        $query = "SELECT * FROM {$this->table_name} WHERE seller_id = :seller_id";
        return $this->query($query, ['seller_id' => $seller_id]);
    }

    public function getById($id, $id_column = 'car_id') {
        return parent::getById($id, $id_column);
    }

    public function update($id, $entity, $id_column = 'car_id') {
        return parent::update($id, $entity, $id_column);
    }

    public function delete($id, $id_column = 'car_id') {
        return parent::delete($id, $id_column);
    }
}