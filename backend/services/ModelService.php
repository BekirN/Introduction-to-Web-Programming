<?php
require_once __DIR__ . '/../dao/ModelDao.php';

class ModelService extends BaseService {
    public function __construct() {
        parent::__construct(new ModelDao());
    }

    public function get_models($vehicle_type = null) {
        if ($vehicle_type) {
            return $this->dao->get_by_vehicle_type($vehicle_type);
        }
        return $this->dao->getAll();
    }


    public function get_model_by_id($id) {
        return $this->dao->getById($id);
    }


    public function add_model($data) {
        return $this->dao->add($data);
    }

    public function update_model($id, $data) {
        return $this->dao->update($data, $id);
    }

    public function delete_model($id) {
        return $this->dao->delete($id);
    }

    public function get_models_by_brand_id($brand_id) {
        return $this->dao->get_by_brand_id($brand_id);
    }
}
