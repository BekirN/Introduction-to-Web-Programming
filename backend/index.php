<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type, authentication");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");


// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}//
require 'vendor/autoload.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/dao/BaseDao.php';
require_once __DIR__ . '/dao/AuthDao.php';
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
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/data/Roles.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



Flight::register('brand_service', 'BrandService');
Flight::register('userService', 'UserService');
Flight::register('modelService', 'ModelService');
Flight::register('carService', 'CarService');
Flight::register('transactionService', 'TransactionService');
Flight::register('auth_service', 'AuthService'); 
Flight::register('auth_middleware', "AuthMiddleware");

Flight::route('/*', function() {
    if (
        strpos(Flight::request()->url, '/auth/login') === 0 ||
        strpos(Flight::request()->url, '/auth/register') === 0
    ) {
        return TRUE;
    } else {
        try {
            $authHeader = Flight::request()->getHeader("Authentication");

            if (Flight::auth_middleware()->verifyToken($authHeader)) {
                return TRUE;
            } else {
                Flight::halt(401, "Invalid token");
            }
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }
});









require_once __DIR__ . '/routes/BrandRoute.php';
require_once __DIR__ . '/routes/UserRoute.php';
require_once __DIR__ . '/routes/ModelRoute.php';
require_once __DIR__ . '/routes/CarRoute.php';
require_once __DIR__ . '/routes/TransactionRoute.php';
require_once __DIR__ . '/routes/AuthRoute.php'; 
Flight::start();