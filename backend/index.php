<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/dao/BaseDao.php';
require_once __DIR__ . '/dao/BrandDao.php';
require_once __DIR__ . '/dao/UserDao.php';
require_once __DIR__ . '/dao/ModelDao.php';
require_once __DIR__ . '/dao/CarDao.php';
require_once __DIR__ . '/dao/TransactionDao.php';
require_once __DIR__ . '/services/BaseService.php';
require_once __DIR__ . '/services/BrandService.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/services/ModelService.php';
require_once __DIR__ . '/services/CarService.php';
require_once __DIR__ . '/services/TransactionService.php';
require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/routes/BrandRoute.php';
require_once __DIR__ . '/routes/UserRoute.php';
require_once __DIR__ . '/routes/ModelRoute.php';
require_once __DIR__ . '/routes/CarRoute.php';
require_once __DIR__ . '/routes/TransactionRoute.php';


Flight::route('/*', function(){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    if (Flight::request()->method == 'OPTIONS') {
        Flight::halt(200);
    }
});
Flight::register('brand_service', 'BrandService', [new BrandDao('brands')]);
Flight::register('user_service', 'UserService', [new UserDao('users')]);
Flight::register('model_service', 'ModelService', [new ModelDao('models')]);
Flight::register('car_service', 'CarService', [new CarDao('cars')]);
Flight::register('transaction_service', 'TransactionService', [new TransactionDao('transactions')]);


Flight::route('GET /api-docs', function() {
    $yaml = file_get_contents(__DIR__ . '/openapi.yaml');
    Flight::json(yaml_parse($yaml));
});
Flight::start();
?>