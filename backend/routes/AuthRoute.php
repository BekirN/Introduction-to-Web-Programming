<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::group('/auth', function() {

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register new user.",
     *     description="Add a new user to the database.",
     *     tags={"auth"},
     *     security={{"ApiKey":{}}},
     *     @OA\RequestBody(
     *         description="Add new user",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"password", "email"},
     *                  @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     example="BekirNokic",
     *                     description="Username"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="demo@gmail.com",
     *                     description="User email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="some_password",
     *                     description="User "
     *                 ),
     *                  @OA\Property(
     *                     property="first_name",
     *                     type="string",
     *                     example="Bekir",
     *                     description="User firstname"
     *                 ),
     *                  @OA\Property(
     *                     property="last_name",
     *                     type="string",
     *                     example="Nokic",
     *                     description="User lastname"
     *                 ),
     *                  @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     example="+123123123",
     *                     description="User password"
     *                 ),
     *                  @OA\Property(
     *                     property="registration_date",
     *                     type="string",
     *                     format = "date-time",
     *                     example="2025-05-18 14:30:00",
     *                     description="Date user registered"
     *                 ),
     *                  @OA\Property(
     *                     property="is_seller",
     *                     type="integer",
     *                     example=1,
     *                     description="Is user seller or not"
     *                 ),
     *                  @OA\Property(
     *                     property="role",
     *                     type="string",
     *                     example="user",
     *                     description="User role"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User has been added."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route("POST /register", function () {
        $data = Flight::request()->data->getData();

        $response = Flight::auth_service()->register($data);
    
        if ($response['success']) {
            Flight::json([
                'message' => 'User registered successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(500, $response['error']);
        }
    });
    
    /**
     * @OA\Post(
     *      path="/auth/login",
     *      tags={"auth"},
     *      summary="Login to system using email and password",
     *      @OA\Response(
     *           response=200,
     *           description="User data and JWT"
     *      ),
     *      @OA\RequestBody(
     *          description="Credentials",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", example="demo@gmail.com", description="User email address"),
     *              @OA\Property(property="password", type="string", example="some_password", description="User password")
     *          )
     *      )
     * )
     */
    Flight::route('POST /login', function() {
        $data = Flight::request()->data->getData();

        $response = Flight::auth_service()->login($data);
    
        if ($response['success']) {
            Flight::json([
                'message' => 'User logged in successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(500, $response['error']);
        }
    }); 
});


?>