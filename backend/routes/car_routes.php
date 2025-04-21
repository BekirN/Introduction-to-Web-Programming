<?php
require_once __DIR__.'/../services/CarService.php';

Flight::route('GET /api/cars', function() {
    $service = new CarService();
    $filters = Flight::request()->query->getData();
    Flight::json($service->getAllListings($filters));
});

Flight::route('GET /api/cars/@id', function($id) {
    $service = new CarService();
    Flight::json($service->getListingDetails($id));
});