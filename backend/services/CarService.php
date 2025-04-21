<?php
require_once __DIR__.'/BaseService.php';
require_once __DIR__.'/../dao/CarDao.php';

class CarService extends BaseService {
    public function __construct() {
        parent::__construct(new CarDao());
    }

    public function getCarDetails($car_id) {
        $car = $this->dao->getById($car_id);
        $car['seller'] = $this->dao->getSellerInfo($car_id);
        $car['model_info'] = $this->dao->getModelInfo($car_id);
        return $car;
    }

    public function searchCars($filters) {
        $validFilters = $this->validateFilters($filters);
        return $this->dao->search($validFilters);
    }

    private function validateFilters($filters) {
        $allowed = ['brand_id', 'model_id', 'min_year', 'max_year', 
                   'min_price', 'max_price', 'color', 'state'];
        return array_intersect_key($filters, array_flip($allowed));
    }

    public function markAsSold($car_id) {
        return $this->dao->update($car_id, ['is_sold' => 1]);
    }
}