<?php
require_once 'BaseDao.php';

class TransactionDao extends BaseDao {
    public function __construct()
    {
        parent::__construct("transactions");
    }
    public function get_by_payment_method($payment_method) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table_name WHERE payment_method = :payment_method");
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getByBuyerId($buyer_id) {
        $stmt = $this->connection->prepare("SELECT * FROM transactions WHERE buyer = :buyer");
        $stmt->bindParam(':buyer', $buyer_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBySellerId($seller_id) {
        $stmt = $this->connection->prepare("SELECT * FROM transactions WHERE seller_user = :seller_user");
        $stmt->bindParam(':seller_user', $seller_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
