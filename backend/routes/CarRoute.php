<?php

Flight::group('/cars', function() {
    /**
     * @OA\Get(
     *     path="/cars",
     *     summary="List all cars",
     *     description="Retrieve a list of all cars",
     *     tags={"cars"},
     *     @OA\Response(
     *         response=200,
     *         description="List of cars",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Cars retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    Flight::route('GET /', function() {
        try {
            header('Content-Type: application/json');
            $car_service = Flight::car_service();
            if (!$car_service) throw new Exception('Car service not initialized');
            $response = $car_service->getAll();
            if ($response['success']) {
                Flight::json(['message' => 'Cars retrieved successfully', 'data' => $response['data']]);
            } else {
                Flight::halt(500, json_encode(['error' => $response['message']]));
            }
        } catch (Exception $e) {
            error_log('Error in GET /cars: ' . $e->getMessage());
            Flight::halt(500, json_encode(['error' => 'Database operation failed', 'details' => $e->getMessage()]));
        }
    });

    /**
     * @OA\Get(
     *     path="/cars/{id}",
     *     summary="Get a car by ID",
     *     description="Retrieve a specific car by its ID",
     *     tags={"cars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Car ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Car details", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="message", type="string", example="Car retrieved successfully"),
     *         @OA\Property(property="data")
     *     )),
     *     @OA\Response(response=404, description="Car not found", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="error", type="string", example="Car not found")
     *     )),
     *     @OA\Response(response=500, description="Internal server error", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="error", type="string", example="Internal server error")
     *     ))
     * )
     */
    Flight::route('GET /@id', function($id) {
        try {
            header('Content-Type: application/json');
            $car_service = Flight::car_service();
            if (!$car_service) throw new Exception('Car service not initialized');
            $response = $car_service->getById($id, 'car_id');
            if ($response['success']) {
                Flight::json(['message' => 'Car retrieved successfully', 'data' => $response['data']]);
            } else {
                $status = $response['message'] === 'Record not found' ? 404 : 500;
                Flight::halt($status, json_encode(['error' => $response['message']]));
            }
        } catch (Exception $e) {
            error_log('Error in GET /cars/{id}: ' . $e->getMessage());
            Flight::halt(500, json_encode(['error' => 'Database operation failed', 'details' => $e->getMessage()]));
        }
    });

    /**
     * @OA\Post(
     *     path="/cars",
     *     summary="Create a new car",
     *     description="Add a new car to the database",
     *     tags={"cars"},
     *     @OA\RequestBody(
     *         description="Car data",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"seller_id", "model_id", "price"},
     *             @OA\Property(property="seller_id", type="integer", example=1, description="Seller ID"),
     *             @OA\Property(property="model_id", type="integer", example=1, description="Model ID"),
     *             @OA\Property(property="price", type="number", format="float", example=20000.00, description="Price"),
     *             @OA\Property(property="mileage", type="integer", example=5000, description="Mileage"),
     *             @OA\Property(property="color", type="string", example="Blue", description="Color"),
     *             @OA\Property(property="state", type="string", enum={"New", "Used", "Certified Pre-Owned"}, example="Used", description="State"),
     *             @OA\Property(property="description", type="string", example="Well-maintained car", description="Description"),
     *             @OA\Property(property="is_sold", type="boolean", example=false, description="Is sold")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Car created", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="message", type="string", example="Car created successfully"),
     *         @OA\Property(property="data", type="object", @OA\Property(property="id", type="integer", example=1))
     *     )),
     *     @OA\Response(response=400, description="Validation error", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="error", type="string", example="Seller ID, model ID, and price are required")
     *     )),
     *     @OA\Response(response=500, description="Internal server error", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="error", type="string", example="Internal server error")
     *     ))
     * )
     */
    Flight::route('POST /', function() {
        try {
            header('Content-Type: application/json');
            $car_service = Flight::car_service();
            if (!$car_service) throw new Exception('Car service not initialized');
            $data = Flight::request()->data->getData();
            $response = $car_service->create($data);
            if ($response['success']) {
                Flight::json(['message' => 'Car created successfully', 'data' => $response['data']], 201);
            } else {
                $status = strpos($response['message'], 'required') !== false ? 400 : 500;
                Flight::halt($status, json_encode(['error' => $response['message']]));
            }
        } catch (Exception $e) {
            error_log('Error in POST /cars: ' . $e->getMessage());
            Flight::halt(500, json_encode(['error' => 'Database operation failed', 'details' => $e->getMessage()]));
        }
    });

    /**
     * @OA\Put(
     *     path="/cars/{id}",
     *     summary="Update a car",
     *     description="Update an existing car by its ID",
     *     tags={"cars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Car ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Updated car data",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="seller_id", type="integer", example=1, description="Seller ID"),
     *             @OA\Property(property="model_id", type="integer", example=1, description="Model ID"),
     *             @OA\Property(property="price", type="number", format="float", example=20000.00, description="Price"),
     *             @OA\Property(property="mileage", type="integer", example=5000, description="Mileage"),
     *             @OA\Property(property="color", type="string", example="Blue", description="Color"),
     *             @OA\Property(property="state", type="string", enum={"New", "Used", "Certified Pre-Owned"}, example="Used", description="State"),
     *             @OA\Property(property="description", type="string", example="Well-maintained car", description="Description"),
     *             @OA\Property(property="is_sold", type="boolean", example=false, description="Is sold")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Car updated", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="message", type="string", example="Car updated successfully"),
     *         @OA\Property(property="data", type="object", @OA\Property(property="affected_rows", type="integer", example=1))
     *     )),
     *     @OA\Response(response=400, description="Validation error", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="error", type="string", example="Seller ID, model ID, and price cannot be empty")
     *     )),
     *     @OA\Response(response=404, description="Car not found", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="error", type="string", example="Car not found")
     *     )),
     *     @OA\Response(response=500, description="Internal server error", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="error", type="string", example="Internal server error")
     *     ))
     * )
     */
    Flight::route('PUT /@id', function($id) {
        try {
            header('Content-Type: application/json');
            $car_service = Flight::car_service();
            if (!$car_service) throw new Exception('Car service not initialized');
            $data = Flight::request()->data->getData();
            $response = $car_service->update($id, $data, 'car_id');
            if ($response['success']) {
                Flight::json(['message' => 'Car updated successfully', 'data' => $response['data']]);
            } else {
                $status = $response['message'] === 'Record not found' ? 404 : (strpos($response['message'], 'cannot be empty') !== false ? 400 : 500);
                Flight::halt($status, json_encode(['error' => $response['message']]));
            }
        } catch (Exception $e) {
            error_log('Error in PUT /cars/{id}: ' . $e->getMessage());
            Flight::halt(500, json_encode(['error' => 'Database operation failed', 'details' => $e->getMessage()]));
        }
    });

    /**
     * @OA\Delete(
     *     path="/cars/{id}",
     *     summary="Delete a car",
     *     description="Delete a car by its ID",
     *     tags={"cars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Car ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Car deleted", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="message", type="string", example="Car deleted successfully"),
     *         @OA\Property(property="data", type="object", @OA\Property(property="affected_rows", type="integer", example=1))
     *     )),
     *     @OA\Response(response=404, description="Car not found", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="error", type="string", example="Car not found")
     *     )),
     *     @OA\Response(response=500, description="Internal server error", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="error", type="string", example="Internal server error")
     *     ))
     * )
     */
    Flight::route('DELETE /@id', function($id) {
        try {
            header('Content-Type: application/json');
            $car_service = Flight::car_service();
            if (!$car_service) throw new Exception('Car service not initialized');
            $response = $car_service->delete($id, 'car_id');
            if ($response['success']) {
                Flight::json(['message' => 'Car deleted successfully', 'data' => $response['data']]);
            } else {
                $status = $response['message'] === 'Record not found' ? 404 : 500;
                Flight::halt($status, json_encode(['error' => $response['message']]));
            }
        } catch (Exception $e) {
            error_log('Error in DELETE /cars/{id}: ' . $e->getMessage());
            Flight::halt(500, json_encode(['error' => 'Database operation failed', 'details' => $e->getMessage()]));
        }
    });
});