<?php
require_once __DIR__ . '/../dao/CarDao.php';

class CarService extends BaseService {
    public function __construct() {
        parent::__construct(new CarDao());
    }

    public function get_cars($state = null) {
        if ($state) {
            return $this->dao->get_by_state($state);
        }
        return $this->dao->getAll();
    }

    public function get_car_by_id($id) {
        return $this->dao->getById($id);
    }

    public function add_car($data) {
        unset($data['brand']);
        $data['is_sold'] = $data['is_sold'] ?? 0;
        return $this->dao->add($data);
    }


    public function update_car($id, $data) {
        unset($data['brand']);
        $data['is_sold'] = $data['is_sold'] ?? 0;
        return $this->dao->update($data, $id);
    }

    public function delete_car($id) {
        return $this->dao->delete($id);
    }

    public function get_cars_by_seller_id($seller_id) {
        return $this->dao->getBySellerId($seller_id);
    }

    public function get_unsold_cars() {
        return $this->dao->getUnsoldCars();
    }
}
