<?php
abstract class BaseService {
    protected $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }


    public function getAll($filters = [], $order = 'id', $direction = 'ASC', $limit = null, $offset = null) {
        return $this->dao->getAll($filters, $order, $direction, $limit, $offset);
    }

    public function getById($id, $idColumn = 'id') {
        if (empty($id)) {
            throw new InvalidArgumentException("ID cannot be empty");
        }
        return $this->dao->getById($id, $idColumn);
    }

    public function create($data) {
        $this->validateCreateData($data);
        return $this->dao->create($data);
    }

    public function update($id, $data, $idColumn = 'id') {
        if (empty($id)) {
            throw new InvalidArgumentException("ID cannot be empty");
        }
        $this->validateUpdateData($data);
        return $this->dao->update($id, $data, $idColumn);
    }

    public function delete($id, $idColumn = 'id') {
        if (empty($id)) {
            throw new InvalidArgumentException("ID cannot be empty");
        }
        return $this->dao->delete($id, $idColumn);
    }


    public function search($filters = [], $sort = [], $pagination = []) {
        return $this->dao->search($filters, $sort, $pagination);
    }

    public function count($filters = []) {
        return $this->dao->count($filters);
    }

    public function getFeaturedListings($limit = 5) {
        return $this->dao->getFeaturedListings($limit);
    }

    public function getRecentListings($limit = 5) {
        return $this->dao->getRecentListings($limit);
    }

    public function getRelatedItems($itemId, $limit = 4) {
        return $this->dao->getRelatedItems($itemId, $limit);
    }

    public function toggleStatus($id, $statusField = 'is_active') {
        return $this->dao->toggleStatus($id, $statusField);
    }

    public function bulkUpdate($ids, $data) {
        if (empty($ids)) {
            throw new InvalidArgumentException("IDs cannot be empty");
        }
        return $this->dao->bulkUpdate($ids, $data);
    }

 
    public function beginTransaction() {
        $this->dao->beginTransaction();
    }

    public function commit() {
        $this->dao->commit();
    }

    public function rollBack() {
        $this->dao->rollBack();
    }

  
    abstract protected function validateCreateData($data);
    abstract protected function validateUpdateData($data);

    
    protected function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    protected function validateId($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException("Invalid ID format");
        }
        return true;
    }
}