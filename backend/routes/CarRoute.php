<?php
/**
 * @OA\Get(
 *      path="/cars",
 *      tags={"cars"},
 *      summary="Get all cars",
 *      security={{"ApiKey":{}}},
 *      @OA\Parameter(
 *          name="state",
 *          in="query",
 *          required=false,
 *          @OA\Schema(type="string", enum={"New", "Used", "Certified Pre-Owned"}),
 *          description="Optional state to filter cars"
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="Array of all cars"
 *      )
 * )
 */
// Flight::route('GET /cars', function(){
//     Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
//     $state = Flight::request()->query['state'] ?? null;
//     Flight::json(Flight::carService()->get_cars($state));
// });
Flight::route('GET /cars', function(){
    try {
        // Commenting auth check for testing
        // Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);

        $state = Flight::request()->query['state'] ?? null;
        $cars = Flight::carService()->get_cars($state);

        Flight::json($cars);
    } catch (\Throwable $e) {
        // Log error
        error_log("ERROR in /cars: " . $e->getMessage());

        // Return error to client
        Flight::halt(500, "Server Error: " . $e->getMessage());
    }
});


/**
 * @OA\Get(
 *     path="/cars/{id}",
 *     tags={"cars"},
 *     summary="Get car by ID",
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the car",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the car with the given ID"
 *     )
 * )
 */
Flight::route('GET /cars/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::carService()->get_car_by_id($id));
});

/**
 * @OA\Post(
 *     path="/cars",
 *     tags={"cars"},
 *     summary="Add a new car",
 *     security={{"ApiKey":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"seller", "model", "price"},
 *             @OA\Property(property="seller", type="integer", example=1),
 *             @OA\Property(property="model", type="integer", example=1),
 *             @OA\Property(property="price", type="number", format="float", example=25000.00),
 *             @OA\Property(property="mileage", type="integer", example=50000),
 *             @OA\Property(property="color", type="string", example="Blue"),
 *             @OA\Property(property="state", type="string", enum={"New", "Used", "Certified Pre-Owned"}, example="Used"),
 *             @OA\Property(property="description", type="string", example="Well-maintained sedan"),
 *             @OA\Property(property="is_sold", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New car created"
 *     )
 * )
 */
Flight::route('POST /cars', function(){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::carService()->add_car($data));
});

/**
 * @OA\Put(
 *     path="/cars/{id}",
 *     tags={"cars"},
 *     summary="Update an existing car by ID",
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Car ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"seller", "model", "price"},
 *             @OA\Property(property="seller", type="integer", example=1),
 *             @OA\Property(property="model", type="integer", example=1),
 *             @OA\Property(property="price", type="number", format="float", example=25000.00),
 *             @OA\Property(property="mileage", type="integer", example=50000),
 *             @OA\Property(property="color", type="string", example="Blue"),
 *             @OA\Property(property="state", type="string", enum={"New", "Used", "Certified Pre-Owned"}, example="Used"),
 *             @OA\Property(property="description", type="string", example="Updated description"),
 *             @OA\Property(property="is_sold", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Car updated"
 *     )
 * )
 */
Flight::route('PUT /cars/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::carService()->update_car($id, $data));
});

/**
 * @OA\Delete(
 *     path="/cars/{id}",
 *     tags={"cars"},
 *     summary="Delete a car by ID",
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Car ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Car deleted"
 *     )
 * )
 */
Flight::route('DELETE /cars/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::carService()->delete_car($id));
});
?>
