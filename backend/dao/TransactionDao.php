<?php
require_once __DIR__ . '/BaseDao.php';

class TransactionDao extends BaseDao {
    public function __construct() {
        parent::__construct('transactions', 'transaction_id');
    }

    public function findByCarId($car_id) {
        $query = "SELECT * FROM {$this->table_name} WHERE car_id = :car_id";
        return $this->query($query, ['car_id' => $car_id]);
    }

    public function getById($id, $id_column = 'transaction_id') {
        return parent::getById($id, $id_column);
    }

    public function update($id, $entity, $id_column = 'transaction_id') {
        return parent::update($id, $entity, $id_column);
    }

    public function delete($id, $id_column = 'transaction_id') {
        return parent::delete($id, $id_column);
    }
}