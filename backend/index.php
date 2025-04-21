<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';

// Database setup
Flight::register('db', 'PDO', [
    'mysql:host='.DB_HOST.';dbname='.DB_NAME, 
    DB_USER, 
    DB_PASS
], function($db) {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});

// Load routes
foreach (glob(__DIR__.'/routes/*.php') as $routeFile) {
    require_once $routeFile;
}

Flight::start();