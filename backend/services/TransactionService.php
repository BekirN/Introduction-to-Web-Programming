<?php
require_once __DIR__.'/BaseService.php';
require_once __DIR__.'/../dao/TransactionDao.php';

class TransactionService extends BaseService {
    public function __construct() {
        parent::__construct(new TransactionDao());
    }

    public function createTransaction($car_id, $buyer_id, $transaction_data) {
  
        $car = $this->dao->getCarStatus($car_id);
        if ($car['is_sold']) {
            throw new Exception("Car is already sold");
        }

        $transaction_data['car_id'] = $car_id;
        $transaction_data['buyer_id'] = $buyer_id;
        $transaction_data['seller_id'] = $car['seller_id'];
        $transaction_data['sale_price'] = $car['price'];
        
        $transaction = parent::create($transaction_data);
      
        $this->dao->markCarAsSold($car_id);
        
        return $transaction;
    }

    public function getUserTransactions($user_id) {
        return $this->dao->getTransactionsByUser($user_id);
    }
}