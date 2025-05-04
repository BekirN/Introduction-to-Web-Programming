<?php

Flight::group('/brands', function() {
    /**
     * @OA\Get(
     *     path="/brands",
     *     summary="List all brands",
     *     description="Retrieve a list of all brands",
     *     tags={"brands"},
     *     @OA\Response(
     *         response=200,
     *         description="List of brands",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Brands retrieved successfully"),
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
            $brand_service = Flight::brand_service();
            if (!$brand_service) {
                throw new Exception('Brand service not initialized');
            }
            $response = $brand_service->getAll();
            error_log('Brands retrieved: ' . print_r($response['data'], true)); 
            if ($response['success']) {
                Flight::json([
                    'message' => 'Brands retrieved successfully',
                    'data' => $response['data']
                ]);
            } else {
                Flight::halt(500, json_encode([
                    'error' => $response['message']
                ]));
            }
        } catch (Exception $e) {
            error_log('Error in GET /brands: ' . $e->getMessage());
            Flight::halt(500, json_encode([
                'error' => 'Database operation failed',
                'details' => $e->getMessage()
            ]));
        }
    });

    /**
     * @OA\Get(
     *     path="/brands/{id}",
     *     summary="Get a brand by ID",
     *     description="Retrieve a specific brand by its ID",
     *     tags={"brands"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Brand ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Brand retrieved successfully"),
     *             @OA\Property(property="data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Brand not found")
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
    Flight::route('GET /@id', function($id) {
        try {
            header('Content-Type: application/json'); 
            $brand_service = Flight::brand_service();
            if (!$brand_service) {
                throw new Exception('Brand service not initialized');
            }
            $response = $brand_service->getById($id, 'brand_id');
            if ($response['success']) {
                Flight::json([
                    'message' => 'Brand retrieved successfully',
                    'data' => $response['data']
                ]);
            } else {
                $status = $response['message'] === 'Record not found' ? 404 : 500;
                Flight::halt($status, json_encode([
                    'error' => $response['message']
                ]));
            }
        } catch (Exception $e) {
            error_log('Error in GET /brands/{id}: ' . $e->getMessage());
            Flight::halt(500, json_encode([
                'error' => 'Database operation failed',
                'details' => $e->getMessage()
            ]));
        }
    });

    /**
     * @OA\Post(
     *     path="/brands",
     *     summary="Create a new brand",
     *     description="Add a new brand to the database",
     *     tags={"brands"},
     *     @OA\RequestBody(
     *         description="Brand data",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"brand_name"},
     *             @OA\Property(property="brand_name", type="string", example="Toyota", description="Brand name"),
     *             @OA\Property(property="country_of_origin", type="string", example="Japan", description="Country of origin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Brand created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Brand created successfully"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="id", type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Brand name is required")
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
    Flight::route('POST /', function() {
        try {
            header('Content-Type: application/json'); 
            $brand_service = Flight::brand_service();
            if (!$brand_service) {
                throw new Exception('Brand service not initialized');
            }
            $data = Flight::request()->data->getData();
            $response = $brand_service->create($data);
            if ($response['success']) {
                Flight::json([
                    'message' => 'Brand created successfully',
                    'data' => $response['data']
                ], 201);
            } else {
                $status = strpos($response['message'], 'required') !== false ? 400 : 500;
                Flight::halt($status, json_encode([
                    'error' => $response['message']
                ]));
            }
        } catch (Exception $e) {
            error_log('Error in POST /brands: ' . $e->getMessage());
            Flight::halt(500, json_encode([
                'error' => 'Database operation failed',
                'details' => $e->getMessage()
            ]));
        }
    });

    /**
     * @OA\Put(
     *     path="/brands/{id}",
     *     summary="Update a brand",
     *     description="Update an existing brand by its ID",
     *     tags={"brands"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Brand ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Updated brand data",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="brand_name", type="string", example="Honda", description="Brand name"),
     *             @OA\Property(property="country_of_origin", type="string", example="Japan", description="Country of origin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Brand updated successfully"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="affected_rows", type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Brand name cannot be empty")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Brand not found")
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
    Flight::route('PUT /@id', function($id) {
        try {
            header('Content-Type: application/json'); 
            $brand_service = Flight::brand_service();
            if (!$brand_service) {
                throw new Exception('Brand service not initialized');
            }
            $data = Flight::request()->data->getData();
            $response = $brand_service->update($id, $data, 'brand_id');
            if ($response['success']) {
                Flight::json([
                    'message' => 'Brand updated successfully',
                    'data' => $response['data']
                ]);
            } else {
                $status = $response['message'] === 'Record not found' ? 404 : (strpos($response['message'], 'cannot be empty') !== false ? 400 : 500);
                Flight::halt($status, json_encode([
                    'error' => $response['message']
                ]));
            }
        } catch (Exception $e) {
            error_log('Error in PUT /brands/{id}: ' . $e->getMessage());
            Flight::halt(500, json_encode([
                'error' => 'Database operation failed',
                'details' => $e->getMessage()
            ]));
        }
    });

    /**
     * @OA\Delete(
     *     path="/brands/{id}",
     *     summary="Delete a brand",
     *     description="Delete a brand by its ID",
     *     tags={"brands"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Brand ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Brand deleted successfully"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="affected_rows", type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Brand not found")
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
    Flight::route('DELETE /@id', function($id) {
        try {
            header('Content-Type: application/json'); 
            $brand_service = Flight::brand_service();
            if (!$brand_service) {
                throw new Exception('Brand service not initialized');
            }
            $response = $brand_service->delete($id, 'brand_id');
            if ($response['success']) {
                Flight::json([
                    'message' => 'Brand deleted successfully',
                    'data' => $response['data']
                ]);
            } else {
                $status = $response['message'] === 'Record not found' ? 404 : 500;
                Flight::halt($status, json_encode([
                    'error' => $response['message']
                ]));
            }
        } catch (Exception $e) {
            error_log('Error in DELETE /brands/{id}: ' . $e->getMessage());
            Flight::halt(500, json_encode([
                'error' => 'Database operation failed',
                'details' => $e->getMessage()
            ]));
        }
    });
});