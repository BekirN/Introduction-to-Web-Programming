<?php

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Car Marketplace API",
 *     description="Swagger documentation for the car marketplace project"
 * )
 */

/**
 * ===================== BRANDS =====================
 */

/**
 * @OA\Get(
 *     path="/brands",
 *     tags={"brands"},
 *     summary="Get all car brands",
 *     @OA\Response(
 *         response=200,
 *         description="Array of all car brands"
 *     )
 * )
 */

/**
 * ===================== MODELS =====================
 */

/**
 * @OA\Get(
 *     path="/models",
 *     tags={"models"},
 *     summary="Get all car models",
 *     @OA\Response(
 *         response=200,
 *         description="Array of all car models"
 *     )
 * )
 */

/**
 * ===================== CARS =====================
 */

/**
 * @OA\Get(
 *     path="/cars",
 *     tags={"cars"},
 *     summary="Get all cars",
 *     @OA\Response(
 *         response=200,
 *         description="List of cars available in the marketplace"
 *     )
 * )
 */

/**
 * @OA\Post(
 *     path="/cars",
 *     tags={"cars"},
 *     summary="Add a new car",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"brand_id", "model_id", "year", "price"},
 *             @OA\Property(property="brand_id", type="integer"),
 *             @OA\Property(property="model_id", type="integer"),
 *             @OA\Property(property="year", type="integer"),
 *             @OA\Property(property="price", type="number")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New car added"
 *     )
 * )
 */

/**
 * @OA\Get(
 *     path="/cars/{id}",
 *     tags={"cars"},
 *     summary="Get a specific car by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Car data by ID"
 *     )
 * )
 */

/**
 * @OA\Delete(
 *     path="/cars/{id}",
 *     tags={"cars"},
 *     summary="Delete a car by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Car deleted"
 *     )
 * )
 */

/**
 * ===================== TRANSACTIONS =====================
 */

/**
 * @OA\Get(
 *     path="/transactions",
 *     tags={"transactions"},
 *     summary="Get all transactions",
 *     @OA\Response(
 *         response=200,
 *         description="List of car purchase transactions"
 *     )
 * )
 */

/**
 * @OA\Post(
 *     path="/transactions",
 *     tags={"transactions"},
 *     summary="Create a new transaction",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "car_id", "price"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="car_id", type="integer"),
 *             @OA\Property(property="price", type="number")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Transaction created"
 *     )
 * )
 */

/**
 * ===================== USERS =====================
 */

/**
 * @OA\Get(
 *     path="/users",
 *     tags={"users"},
 *     summary="Get all users",
 *     @OA\Response(
 *         response=200,
 *         description="List of users"
 *     )
 * )
 */



