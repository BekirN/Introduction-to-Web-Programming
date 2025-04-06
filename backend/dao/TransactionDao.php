<?php
require_once __DIR__ . "/BaseDao.php";

class TransactionDao extends BaseDao {
    public function __construct() {
        parent::__construct("transactions");
    }

    public function findByBuyer($buyer_id) {
        return $this->query("SELECT * FROM transactions WHERE buyer_id = :buyer_id", ["buyer_id" => $buyer_id]);
    }

    public function findBySeller($seller_id) {
        return $this->query("SELECT * FROM transactions WHERE seller_id = :seller_id", ["seller_id" => $seller_id]);
    }

    public function findByCarId($car_id) {
        return $this->query("SELECT * FROM transactions WHERE car_id = :car_id", ["car_id" => $car_id]);
    }
    public function insert($entity) {
        return $this->add($entity);
    }
}
