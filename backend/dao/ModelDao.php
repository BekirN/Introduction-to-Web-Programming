<?php
require_once 'BaseDao.php';

class ModelDao extends BaseDao {
    public function __construct()
    {
        parent::__construct("models");
    }

    public function getByBrandId($brandId) {
        $stmt = $this->connection->prepare("SELECT * FROM models WHERE brand = :brand");
        $stmt->bindParam(':brand', $brandId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_by_vehicle_type($type) {
        $query = "SELECT * FROM models WHERE vehicle_type = :type";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
