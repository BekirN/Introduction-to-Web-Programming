<?php

/**
 * @OA\Get(
 *      path="/users",
 *      tags={"users"},
 *      summary="Get all users",
 *      security={{"ApiKey":{}}},
 *      @OA\Parameter(
 *          name="is_seller",
 *          in="query",
 *          required=false,
 *          @OA\Schema(type="boolean"),
 *          description="Optional filter for seller status"
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="Array of all users in the database"
 *      )
 * )
 */
Flight::route('GET /users', function(){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $is_seller = Flight::request()->query['is_seller'] ?? null;
    Flight::json(Flight::userService()->get_users($is_seller));
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Get user by ID",
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the user with the given ID"
 *     )
 * )
 */
Flight::route('GET /users/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::userService()->get_user_by_id($id));
});

/**
 * @OA\Post(
 *     path="/users",
 *     tags={"users"},
 *     summary="Add a new user",
 *     security={{"ApiKey":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "email", "password_hash"},
 *             @OA\Property(property="username", type="string", example="johndoe"),
 *             @OA\Property(property="email", type="string", example="john@example.com"),
 *             @OA\Property(property="password_hash", type="string", example="$2y$10$..."),
 *             @OA\Property(property="first_name", type="string", example="John"),
 *             @OA\Property(property="last_name", type="string", example="Doe"),
 *             @OA\Property(property="phone", type="string", example="1234567890"),
 *             @OA\Property(property="is_seller", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New user created"
 *     )
 * )
 */
Flight::route('POST /users', function(){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->add_user($data));
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Update an existing user by ID",
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "email", "password_hash"},
 *             @OA\Property(property="username", type="string", example="updated_johndoe"),
 *             @OA\Property(property="email", type="string", example="updated_john@example.com"),
 *             @OA\Property(property="password_hash", type="string", example="$2y$10$..."),
 *             @OA\Property(property="first_name", type="string", example="John"),
 *             @OA\Property(property="last_name", type="string", example="Doe"),
 *             @OA\Property(property="phone", type="string", example="1234567890"),
 *             @OA\Property(property="is_seller", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated"
 *     )
 * )
 */
Flight::route('PUT /users/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->update_user($id, $data));
});



/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Delete a user by ID",
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted"
 *     )
 * )
 */
Flight::route('DELETE /users/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::userService()->delete_user($id));
});

?>