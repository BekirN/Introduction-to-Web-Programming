<?php
Flight::group('/transactions', function() {
    /**
     * @OA\Get(
     *     path="/transactions",
     *     summary="List all transactions",
     *     description="Retrieve a list of all transactions",
     *     tags={"transactions"},
     *     @OA\Response(
     *         response=200,
     *         description="List of transactions",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Transactions retrieved successfully"),
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
            $transaction_service = Flight::transaction_service();
            $response = $transaction_service->getAll();
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'Transactions retrieved successfully',
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
     *     path="/transactions/{id}",
     *     summary="Get a transaction by ID",
     *     description="Retrieve a specific transaction by its ID",
     *     tags={"transactions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transaction ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Transaction retrieved successfully"),
     *             @OA\Property(property="data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Transaction not found")
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
            $transaction_service = Flight::transaction_service();
            $response = $transaction_service->getById($id, 'transaction_id');
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'Transaction retrieved successfully',
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
     *     path="/transactions",
     *     summary="Create a new transaction",
     *     description="Add a new transaction to the database",
     *     tags={"transactions"},
     *     @OA\RequestBody(
     *         description="Transaction data",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"car_id", "buyer_id", "seller_id", "sale_price"},
     *             @OA\Property(property="car_id", type="integer", example=1, description="Car ID"),
     *             @OA\Property(property="buyer_id", type="integer", example=2, description="Buyer ID"),
     *             @OA\Property(property="seller_id", type="integer", example=1, description="Seller ID"),
     *             @OA\Property(property="sale_price", type="number", format="float", example=20000.00, description="Sale price"),
     *             @OA\Property(property="payment_method", type="string", enum={"Cash", "Bank Transfer", "Credit Card", "Loan"}, example="Cash", description="Payment method")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Transaction created successfully"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="id", type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Car ID, buyer ID, seller ID, and sale price are required")
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
            $transaction_service = Flight::transaction_service();
            $data = Flight::request()->data->getData();
            $response = $transaction_service->create($data);
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'Transaction created successfully',
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
     *     path="/transactions/{id}",
     *     summary="Update a transaction",
     *     description="Update an existing transaction by its ID",
     *     tags={"transactions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transaction ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Updated transaction data",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="car_id", type="integer", example=1, description="Car ID"),
     *             @OA\Property(property="buyer_id", type="integer", example=2, description="Buyer ID"),
     *             @OA\Property(property="seller_id", type="integer", example=1, description="Seller ID"),
     *             @OA\Property(property="sale_price", type="number", format="float", example=20000.00, description="Sale price"),
     *             @OA\Property(property="payment_method", type="string", enum={"Cash", "Bank Transfer", "Credit Card", "Loan"}, example="Cash", description="Payment method")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Transaction updated successfully"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="affected_rows", type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Car ID, buyer ID, seller ID, and sale price cannot be empty")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Transaction not found")
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
            $transaction_service = Flight::transaction_service();
            $data = Flight::request()->data->getData();
            $response = $transaction_service->update($id, $data, 'transaction_id');
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'Transaction updated successfully',
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
     *     path="/transactions/{id}",
     *     summary="Delete a transaction",
     *     description="Delete a transaction by its ID",
     *     tags={"transactions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transaction ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Transaction deleted successfully"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="affected_rows", type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Transaction not found")
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
            $transaction_service = Flight::transaction_service();
            $response = $transaction_service->delete($id, 'transaction_id');
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'Transaction deleted successfully',
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