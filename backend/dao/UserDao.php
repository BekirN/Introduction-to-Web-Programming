<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct('users', 'user_id');
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM {$this->table_name} WHERE email = :email";
        return $this->query_unique($query, ['email' => $email]);
    }

    public function getById($id, $id_column = 'user_id') {
        return parent::getById($id, $id_column);
    }

    public function update($id, $entity, $id_column = 'user_id') {
        return parent::update($id, $entity, $id_column);
    }

    public function delete($id, $id_column = 'user_id') {
        return parent::delete($id, $id_column);
    }
}