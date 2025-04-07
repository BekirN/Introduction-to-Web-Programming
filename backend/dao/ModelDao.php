<?php
require_once __DIR__ . "/BaseDao.php";

class ModelDao extends BaseDao {
    public function __construct() {
        parent::__construct("models");
    }

    public function findByBrand($brand_id) {
        return $this->query("SELECT * FROM models WHERE brand_id = :brand_id", ["brand_id" => $brand_id]);
    }

    public function findByNameAndYear($brand_id, $model_name, $year) {
        return $this->query_unique(
            "SELECT * FROM models WHERE brand_id = :brand_id AND model_name = :model_name AND year = :year",
            ["brand_id" => $brand_id, "model_name" => $model_name, "year" => $year]
        );
    }
    public function insert($entity) {
        return $this->add($entity);
    }
}
