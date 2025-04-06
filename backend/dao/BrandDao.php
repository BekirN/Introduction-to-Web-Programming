<?php
require_once __DIR__ . "/BaseDao.php";

class BrandDao extends BaseDao {
    public function __construct() {
        parent::__construct("brands");
    }

    public function findByName($brand_name) {
        return $this->query_unique("SELECT * FROM brands WHERE brand_name = :brand_name", ["brand_name" => $brand_name]);
    }
    public function insert($entity) {
        return $this->add($entity);
    }
}
