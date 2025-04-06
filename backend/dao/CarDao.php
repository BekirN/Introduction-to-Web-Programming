<?php
require_once __DIR__ . "/BaseDao.php";

class CarDao extends BaseDao {
    public function __construct() {
        parent::__construct("cars");
    }

    public function findAvailableCars() {
        return $this->query("SELECT * FROM cars WHERE is_sold = FALSE", []);
    }

    public function findBySeller($seller_id) {
        return $this->query("SELECT * FROM cars WHERE seller_id = :seller_id", ["seller_id" => $seller_id]);
    }
    public function insert($entity) {
        return $this->add($entity);
    }
}
