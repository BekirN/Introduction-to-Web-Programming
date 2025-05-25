<?php
require_once __DIR__ . '/../dao/TransactionDao.php';

class TransactionService extends BaseService {
    public function __construct() {
        parent::__construct(new TransactionDao());
    }

    public function get_transactions($payment_method = null) {
        if ($payment_method) {
            return $this->dao->get_by_payment_method($payment_method);
        }
        return $this->dao->getAll();
    }

    public function get_transaction_by_id($id) {
        return $this->dao->getById($id); 
    }

    public function add_transaction($data) {
        return $this->dao->add($data); 
    }

    public function delete_transaction($id) {
        return $this->dao->delete($id); 
    }

    public function get_transactions_by_buyer_id($buyer_id) {
        return $this->dao->get_by_buyer_id($buyer_id);
    }

    public function get_transactions_by_seller_id($seller_id) {
        return $this->dao->get_by_seller_id($seller_id);
    }
}
