<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../vendor/autoload.php';
define('LOCALSERVER', 'http://localhost/BekirNokic/Introduction-to-Web-Programming/backend/');
define('PRODSERVER', 'https://add-production-server-after-deployment/backend/');
define('BASE_URL', ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') ? LOCALSERVER : PRODSERVER);
//problems with swagger version  
try {
    $openapi = \OpenApi\Generator::scan([
        __DIR__ . '/doc_setup.php', 
        __DIR__ . '/../../../routes' 
    ]);

    $json = $openapi->toJson();
    if (json_decode($json) === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new RuntimeException('Invalid JSON generated: ' . json_last_error_msg());
    }

    ob_end_clean();
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    
    echo $json;

} catch (Exception $e) {
    ob_end_clean();
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to generate OpenAPI documentation',
        'message' => $e->getMessage()
    ]);
    exit;
}