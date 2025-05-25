<?php
require_once 'BaseDao.php';

class CarDao extends BaseDao {
    public function __construct()
    {
        parent::__construct("cars");
    }

    public function getBySellerId($seller_id) {
        $stmt = $this->connection->prepare("SELECT * FROM cars WHERE seller = :seller");
        $stmt->bindParam(':seller', $seller_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUnsoldCars() {
        $stmt = $this->connection->prepare("SELECT * FROM cars WHERE is_sold = 0");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_by_state($state) {
        $query = "SELECT * FROM cars WHERE state = :state";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':state', $state);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
     public function getAll() {
        $sql = "
            SELECT 
                c.id, c.price, c.mileage, c.color, c.state, c.description,
                m.id AS model_id, m.model_name,
                b.id AS brand_id, b.brand_name
            FROM cars c
            JOIN models m ON c.model = m.id
            JOIN brands b ON m.brand = b.id
        ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $cars = [];
        foreach ($rows as $row) {
            $cars[] = [
                'id' => (int)$row['id'],
                'price' => (float)$row['price'],
                'mileage' => (int)$row['mileage'],
                'color' => $row['color'],
                'state' => $row['state'],
                'description' => $row['description'],
                'model' => [
                    'id' => (int)$row['model_id'],
                    'model_name' => $row['model_name'],
                    'brand' => [
                        'id' => (int)$row['brand_id'],
                        'brand_name' => $row['brand_name'],
                    ]
                ]
            ];
        }
        return $cars;
    }
}
