<?php

/**
 * @OA\Get(
 *      path="/models",
 *      tags={"models"},
 *      summary="Get all models",
 *      security={{"ApiKey":{}}},
 *      @OA\Parameter(
 *          name="vehicle_type",
 *          in="query",
 *          required=false,
 *          @OA\Schema(type="string", enum={"Sedan", "SUV", "Truck", "Coupe", "Hatchback", "Convertible"}),
 *          description="Optional vehicle type to filter models"
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="Array of all models in the database"
 *      )
 * )
 */
Flight::route('GET /models', function(){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $vehicle_type = Flight::request()->query['vehicle_type'] ?? null;
    Flight::json(Flight::modelService()->get_models($vehicle_type));
});

/**
 * @OA\Get(
 *     path="/models/{id}",
 *     tags={"models"},
 *     summary="Get model by ID",
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the model",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the model with the given ID"
 *     )
 * )
 */
Flight::route('GET /models/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    Flight::json(Flight::modelService()->get_model_by_id($id));
});

/**
 * @OA\Post(
 *     path="/models",
 *     tags={"models"},
 *     summary="Add a new model",
 *     security={{"ApiKey":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"brand_id", "model_name"},
 *             @OA\Property(property="brand_id", type="integer", example=1),
 *             @OA\Property(property="model_name", type="string", example="Camry"),
 *             @OA\Property(property="year", type="integer", example=2023),
 *             @OA\Property(property="vehicle_type", type="string", enum={"Sedan", "SUV", "Truck", "Coupe", "Hatchback", "Convertible"}, example="Sedan")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New model created"
 *     )
 * )
 */
Flight::route('POST /models', function(){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::modelService()->add_model($data));
});

/**
 * @OA\Put(
 *     path="/models/{id}",
 *     tags={"models"},
 *     summary="Update an existing model by ID",
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Model ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"brand_id", "model_name"},
 *             @OA\Property(property="brand_id", type="integer", example=1),
 *             @OA\Property(property="model_name", type="string", example="Updated Camry"),
 *             @OA\Property(property="year", type="integer", example=2023),
 *             @OA\Property(property="vehicle_type", type="string", enum={"Sedan", "SUV", "Truck", "Coupe", "Hatchback", "Convertible"}, example="Sedan")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Model updated"
 *     )
 * )
 */
Flight::route('PUT /models/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::modelService()->update_model($id, $data));
});

/**
 * @OA\Delete(
 *     path="/models/{id}",
 *     tags={"models"},
 *     summary="Delete a model by ID",
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Model ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Model deleted"
 *     )
 * )
 */
Flight::route('DELETE /models/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::modelService()->delete_model($id));
});

?>