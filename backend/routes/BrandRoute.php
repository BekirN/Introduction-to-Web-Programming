<?php

/**
 * @OA\Tag(
 *     name="brands",
 *     description="Operations about car brands"
 * )
 */

Flight::group('/brands', function() {

    /**
     * @OA\Get(
     *     path="/brands",
     *     summary="Get all brands",
     *     security={{"ApiKey":{}}},
     *     tags={"brands"},
     *     @OA\Response(
     *         response=200,
     *         description="Array of brand objects"
     *     )
     * )
     */
    Flight::route('GET /', function () {
        Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
        Flight::json(Flight::brand_service()->getAll());
    });

    /**
     * @OA\Get(
     *     path="/brands/{id}",
     *     summary="Get brand by ID",
     *     security={{"ApiKey":{}}},
     *     tags={"brands"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand object"
     *     )
     * )
     */
    Flight::route('GET /@id', function ($id) {
        Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
        Flight::json(Flight::brand_service()->getById($id));
    });

    /**
     * @OA\Post(
     *     path="/brands",
     *     summary="Add new brand",
     *     security={{"ApiKey":{}}},
     *     tags={"brands"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"brand_name"},
     *             @OA\Property(property="brand_name", type="string", example="Toyota"),
     *             @OA\Property(property="country_of_origin", type="string", example="Japan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Brand created"
     *     )
     * )
     */
    Flight::route('POST /', function () {
        Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
        Flight::json(Flight::brand_service()->create(Flight::request()->data->getData()), 201);
    });

    /**
     * @OA\Put(
     *     path="/brands/{id}",
     *     summary="Update brand",
     *     security={{"ApiKey":{}}},
     *     tags={"brands"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="brand_name", type="string", example="Nissan"),
     *             @OA\Property(property="country_of_origin", type="string", example="Japan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand updated"
     *     )
     * )
     */
    Flight::route('PUT /@id', function ($id) {
        Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
        $data = Flight::request()->data->getData();
        Flight::json(Flight::brand_service()->update($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/brands/{id}",
     *     summary="Delete brand",
     *     security={{"ApiKey":{}}},
     *     tags={"brands"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand deleted"
     *     )
     * )
     */
    Flight::route('DELETE /@id', function ($id) {
        Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
        Flight::json(Flight::brand_service()->delete($id));
    });

});
