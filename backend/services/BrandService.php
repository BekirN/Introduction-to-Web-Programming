<?php
require_once __DIR__ . '/../dao/BrandDao.php';

class BrandService extends BaseService {
    public function __construct() {
        parent::__construct(new BrandDao());
    }

    public function get_brand_by_name($name) {
        return $this->dao->get_by_name($name);
    }

    public function getById($id, $id_column = 'id') {
        return $this->dao->getById($id, $id_column);
    }

    public function update($id, $data, $id_column = "id") {
    return $this->dao->update($data, $id, $id_column);
    }

  
    public function delete($id, $id_column = 'id') {
        return $this->dao->delete($id, $id_column);
    }

    
    public function create($entity) {
        return $this->add($entity); 
    }
}
