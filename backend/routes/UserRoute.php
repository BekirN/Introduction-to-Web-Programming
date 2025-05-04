<?php
Flight::group('/users', function() {
    /**
     * @OA\Get(
     *     path="/users",
     *     summary="List all users",
     *     description="Retrieve a list of all users",
     *     tags={"users"},
     *     @OA\Response(
     *         response=200,
     *         description="List of users",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Users retrieved successfully"),
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
            $user_service = Flight::user_service();
            $response = $user_service->getAll();
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'Users retrieved successfully',
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
     *     path="/users/{id}",
     *     summary="Get a user by ID",
     *     description="Retrieve a specific user by its ID",
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="User retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="User not found")
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
            $user_service = Flight::user_service();
            $response = $user_service->getById($id, 'user_id');
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'User retrieved successfully',
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
     *     path="/users",
     *     summary="Create a new user",
     *     description="Add a new user to the database",
     *     tags={"users"},
     *     @OA\RequestBody(
     *         description="User data",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "email", "password_hash"},
     *             @OA\Property(property="username", type="string", example="john_doe", description="Username"),
     *             @OA\Property(property="email", type="string", example="john@example.com", description="Email"),
     *             @OA\Property(property="password_hash", type="string", example="hashedpassword", description="Password hash"),
     *             @OA\Property(property="first_name", type="string", example="John", description="First name"),
     *             @OA\Property(property="last_name", type="string", example="Doe", description="Last name"),
     *             @OA\Property(property="phone", type="string", example="1234567890", description="Phone number"),
     *             @OA\Property(property="is_seller", type="boolean", example=true, description="Is the user a seller")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="id", type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Username, email, and password hash are required")
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
            $user_service = Flight::user_service();
            $data = Flight::request()->data->getData();
            $response = $user_service->create($data);
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'User created successfully',
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
     *     path="/users/{id}",
     *     summary="Update a user",
     *     description="Update an existing user by its ID",
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Updated user data",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string", example="john_doe", description="Username"),
     *             @OA\Property(property="email", type="string", example="john@example.com", description="Email"),
     *             @OA\Property(property="password_hash", type="string", example="hashedpassword", description="Password hash"),
     *             @OA\Property(property="first_name", type="string", example="John", description="First name"),
     *             @OA\Property(property="last_name", type="string", example="Doe", description="Last name"),
     *             @OA\Property(property="phone", type="string", example="1234567890", description="Phone number"),
     *             @OA\Property(property="is_seller", type="boolean", example=true, description="Is the user a seller")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="User updated successfully"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="affected_rows", type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Username, email, and password hash cannot be empty")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="User not found")
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
            $user_service = Flight::user_service();
            $data = Flight::request()->data->getData();
            $response = $user_service->update($id, $data, 'user_id');
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'User updated successfully',
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
     *     path="/users/{id}",
     *     summary="Delete a user",
     *     description="Delete a user by its ID",
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="User deleted successfully"),
     *             @OA\Property(property="data", type="object", @OA\Property(property="affected_rows", type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="User not found")
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
            $user_service = Flight::user_service();
            $response = $user_service->delete($id, 'user_id');
            
            if ($response['success']) {
                Flight::json([
                    'message' => 'User deleted successfully',
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
