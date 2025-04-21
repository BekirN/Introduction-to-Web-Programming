<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.'/../dao/config.php';

require_once __DIR__.'/BaseService.php';
require_once __DIR__.'/BrandService.php';
require_once __DIR__.'/../dao/BrandDao.php';
require_once __DIR__.'/../dao/BaseDao.php';

try {
    echo "=== Testing Brand Service ===\n";
    

    $db = Database::connect();
    

    $brandDao = new BrandDao($db);
    
   
    $brandService = new BrandService($brandDao);

    $testBrand = [
        'brand_name' => 'TestBrand_'.time(),
        'country_of_origin' => 'TestCountry'
    ];

    echo "Creating brand...\n";
    $newBrand = $brandService->create($testBrand);
    print_r($newBrand);

    echo "\nFetching brand...\n";
    $fetchedBrand = $brandService->getById($newBrand['brand_id']);
    print_r($fetchedBrand);

    echo "\nDeleting test brand...\n";
    $brandService->delete($newBrand['brand_id']);
    echo "Test completed successfully!\n";
    
} catch (Exception $e) {
    echo "\nTEST FAILED: ".$e->getMessage()."\n";
    if (isset($newBrand['brand_id'])) {
        try {
            $brandService->delete($newBrand['brand_id']);
            echo "Cleanup performed\n";
        } catch (Exception $cleanupEx) {
            echo "Cleanup failed: ".$cleanupEx->getMessage()."\n";
        }
    }
}