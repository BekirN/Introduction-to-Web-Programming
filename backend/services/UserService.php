<?php
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService {
    public function __construct() {
        parent::__construct(new UserDao());
    }

    public function get_users($is_seller = null) {
        if ($is_seller !== null) {
            return $this->dao->get_all_sellers(); 
        }
        return $this->dao->getAll();
    }

 
    public function get_user_by_id($id) {
        return $this->dao->getById($id);
    }

    public function add_user($data) {
        return $this->dao->add($data);
    }


    public function update_user($id, $data) {
        return $this->dao->update($data, $id);
    }

    public function delete_user($id) {
        return $this->dao->delete($id);
    }

   
    public function get_user_by_username($username) {
        return $this->dao->get_user_by_username($username);
    }

   
    public function get_all_sellers() {
        return $this->dao->get_all_sellers();
    }
}
