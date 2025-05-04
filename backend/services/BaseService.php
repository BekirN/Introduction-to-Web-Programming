<?php
abstract class BaseService {
    protected $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

    protected function standardResponse($data, $success = true, $message = null) {
        return [
            'success' => $success,
            'data' => $data,
            'message' => $message ?? ($success ? 'Operation successful' : 'Operation failed')
        ];
    }

    public function getAll() {
        try {
            $data = $this->dao->getAll();
            return $this->standardResponse($data);
        } catch (Exception $e) {
            return $this->standardResponse(null, false, $e->getMessage());
        }
    }

    public function getById($id, $idColumn = 'id') {
        try {
            $data = $this->dao->getById($id, $idColumn);
            if (!$data) {
                throw new Exception("Record not found");
            }
            return $this->standardResponse($data);
        } catch (Exception $e) {
            return $this->standardResponse(null, false, $e->getMessage());
        }
    }

    public function create($data) {
        try {
            $this->validateCreateData($data);
            $result = $this->dao->add($data);
            return $this->standardResponse(['id' => $result]);
        } catch (Exception $e) {
            return $this->standardResponse(null, false, $e->getMessage());
        }
    }

    public function update($id, $data, $idColumn = 'id') {
        try {
            $this->validateUpdateData($data);
            $result = $this->dao->update($id, $data, $idColumn);
            return $this->standardResponse($result > 0 ? ['affected_rows' => $result] : null, $result > 0);
        } catch (Exception $e) {
            return $this->standardResponse(null, false, $e->getMessage());
        }
    }

    public function delete($id, $idColumn = 'id') {
        try {
            $result = $this->dao->delete($id, $idColumn);
            return $this->standardResponse(['affected_rows' => $result], $result > 0);
        } catch (Exception $e) {
            return $this->standardResponse(null, false, $e->getMessage());
        }
    }

    abstract protected function validateCreateData($data);
    abstract protected function validateUpdateData($data);
}