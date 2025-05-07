<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


Flight::group('/models', function() {
    /**
     * @OA\Get(
     *     path="/models",
     *     summary="List all models",
     *     description="Retrieve a list of all models",
     *     tags={"models"},
     *     @OA\Response(
     *         response=200,
     *         description="List of models",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Models retrieved successfully"),
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
            $model_service = Flight::model_service();
            $response = $model_service->getAll();
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'Models retrieved successfully',
                    'data' => $response['data']
                ]);
            } else {
                Flight::halt(500, json_encode([
                    'error' => $response['message']
                ]));
            }
        } catch (Exception $e) {
            Flight::halt(500, json_encode([
                'error' => 'Database operation failed',
                'details' => $e->getMessage()
            ]));
        }
    });

    /**
     * @OA\Get(
     *     path="/models/{id}",
     *     summary="Get a model by ID",
     *     description="Retrieve a specific model by its ID",
     *     tags={"models"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Model ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Model details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Model retrieved successfully"),
     *             @OA\Property(property="data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Model not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Model not found")
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
            $model_service = Flight::model_service();
            $response = $model_service->getById($id, 'model_id');
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'Model retrieved successfully',
                    'data' => $response['data']
                ]);
            } else {
                $status = $response['message'] === 'Record not found' ? 404 : 500;
                Flight::halt($status, json_encode([
                    'error' => $response['message']
                ]));
            }
        } catch (Exception $e) {
            Flight::halt(500, json_encode([
                'error' => 'Database operation failed',
                'details' => $e->getMessage()
            ]));
        }
    });

    /**
     * @OA\Post(
     *     path="/models",
     *     summary="Create a new model",
     *     description="Add a new model to the database",
     *     tags={"models"},
     *     @OA\RequestBody(
     *         description="Model data",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"brand_id", "model_name", "year"},
     *             @OA\Property(property="brand_id", type="integer", example=1, description="Brand ID"),
     *             @OA\Property(property="model_name", type="string", example="Camry", description="Model name"),
     *             @OA\Property(property="year", type="integer", example=2020, description="Year of the model"),
     *             @OA\Property(property="vehicle_type", type="string", enum={"Sedan", "SUV", "Truck", "Coupe", "Hatchback", "Convertible"}, example="Sedan", description="Vehicle type")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Model created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Model created successfully"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="id", type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Brand ID, model name, and year are required")
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
            $model_service = Flight::model_service();
            $data = Flight::request()->data->getData();
            $response = $model_service->create($data);
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'Model created successfully',
                    'data' => $response['data']
                ], 201);
            } else {
                $status = strpos($response['message'], 'required') !== false ? 400 : 500;
                Flight::halt($status, json_encode([
                    'error' => $response['message']
                ]));
            }
        } catch (Exception $e) {
            Flight::halt(500, json_encode([
                'error' => 'Database operation failed',
                'details' => $e->getMessage()
            ]));
        }
    });

    /**
     * @OA\Put(
     *     path="/models/{id}",
     *     summary="Update a model",
     *     description="Update an existing model by its ID",
     *     tags={"models"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Model ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Updated model data",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="brand_id", type="integer", example=1, description="Brand ID"),
     *             @OA\Property(property="model_name", type="string", example="Camry", description="Model name"),
     *             @OA\Property(property="year", type="integer", example=2020, description="Year of the model"),
     *             @OA\Property(property="vehicle_type", type="string", enum={"Sedan", "SUV", "Truck", "Coupe", "Hatchback", "Convertible"}, example="Sedan", description="Vehicle type")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Model updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Model updated successfully"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="affected_rows", type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Brand ID, model name, and year cannot be empty")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Model not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Model not found")
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
            $model_service = Flight::model_service();
            $data = Flight::request()->data->getData();
            $response = $model_service->update($id, $data, 'model_id');
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'Model updated successfully',
                    'data' => $response['data']
                ]);
            } else {
                $status = $response['message'] === 'Record not found' ? 404 : (strpos($response['message'], 'cannot be empty') !== false ? 400 : 500);
                Flight::halt($status, json_encode([
                    'error' => $response['message']
                ]));
            }
        } catch (Exception $e) {
            Flight::halt(500, json_encode([
                'error' => 'Database operation failed',
                'details' => $e->getMessage()
            ]));
        }
    });

    /**
     * @OA\Delete(
     *     path="/models/{id}",
     *     summary="Delete a model",
     *     description="Delete a model by its ID",
     *     tags={"models"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Model ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Model deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Model deleted successfully"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="affected_rows", type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Model not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Model not found")
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
            $model_service = Flight::model_service();
            $response = $model_service->delete($id, 'model_id');
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'Model deleted successfully',
                    'data' => $response['data']
                ]);
            } else {
                $status = $response['message'] === 'Record not found' ? 404 : 500;
                Flight::halt($status, json_encode([
                    'error' => $response['message']
                ]));
            }
        } catch (Exception $e) {
            Flight::halt(500, json_encode([
                'error' => 'Database operation failed',
                'details' => $e->getMessage()
            ]));
        }
    });
});
