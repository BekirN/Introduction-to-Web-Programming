<?php
require_once 'dao/UserDao.php';
require_once 'dao/BrandDao.php';
require_once 'dao/ModelDao.php';
require_once 'dao/CarDao.php';
require_once 'dao/TransactionDao.php';

$userDao = new UserDao();
$brandDao = new BrandDao();
$modelDao = new ModelDao();
$carDao = new CarDao();
$transactionDao = new TransactionDao();

// Insert a test user
$user = $userDao->insert([
    'username' => 'bekir123',
    'email' => 'bekir@example.com',
    'password_hash' => password_hash('securepass', PASSWORD_DEFAULT),
    'first_name' => 'Bekir',
    'last_name' => 'Nokic',
    'phone' => '123456789',
    'is_seller' => true
]);
print_r($user);

// Insert a test brand
$brand = $brandDao->insert([
    'brand_name' => 'Toyota',
    'country_of_origin' => 'Japan'
]);
print_r($brand);

// Insert a test model
$model = $modelDao->insert([
    'brand_id' => $brand['id'],
    'model_name' => 'Corolla',
    'year' => 2021,
    'vehicle_type' => 'Sedan'
]);
print_r($model);

// Insert a car listing
$car = $carDao->insert([
    'seller_id' => $user['id'],
    'model_id' => $model['id'],
    'price' => 15000.00,
    'mileage' => 30000,
    'color' => 'White',
    'state' => 'Used',
    'description' => 'Well-maintained and reliable.'
]);
print_r($car);

// Insert a transaction (simulate a sale)
$buyer = $userDao->insert([
    'username' => 'john_buyer',
    'email' => 'john_buyer@example.com',
    'password_hash' => password_hash('pass456', PASSWORD_DEFAULT),
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '987654321',
    'is_seller' => false
]);

$transaction = $transactionDao->insert([
    'car_id' => $car['id'],
    'buyer_id' => $buyer['id'],
    'seller_id' => $user['id'],
    'sale_price' => 14500.00,
    'payment_method' => 'Cash'
]);
print_r($transaction);

// Fetch all cars
$cars = $carDao->getAll();
print_r($cars);
?>
