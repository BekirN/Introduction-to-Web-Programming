<?php
require_once 'BaseDao.php';

class BrandDao extends BaseDao {
    public function __construct()
    {
        parent::__construct("brands");
    }

    public function getByName($brand_name) {
        $stmt = $this->connection->prepare("SELECT * FROM brands WHERE brand_name = :brand_name");
        $stmt->bindParam(':brand_name', $brand_name);
        $stmt->execute();
        return $stmt->fetch();
    }
    
}
