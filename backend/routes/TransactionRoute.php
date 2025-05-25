<?php

/**
 * @OA\Get(
 *      path="/transactions",
 *      tags={"transactions"},
 *      summary="Get all transactions",
 *      security={{"ApiKey":{}}},
 *      @OA\Parameter(
 *          name="payment_method",
 *          in="query",
 *          required=false,
 *          @OA\Schema(type="string", enum={"Cash", "Bank Transfer", "Credit Card", "Loan"}),
 *          description="Optional payment method to filter transactions"
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="Array of all transactions in the database"
 *      )
 * )
 */
Flight::route('GET /transactions', function(){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $payment_method = Flight::request()->query['payment_method'] ?? null;
    Flight::json(Flight::transactionService()->get_transactions($payment_method));
});

/**
 * @OA\Get(
 *     path="/transactions/{id}",
 *     tags={"transactions"},
 *     summary="Get transaction by ID",
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the transaction",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the transaction with the given ID"
 *     )
 * )
 */
Flight::route('GET /transactions/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::transactionService()->get_transaction_by_id($id));
});

/**
 * @OA\Post(
 *     path="/transactions",
 *     tags={"transactions"},
 *     summary="Add a new transaction",
 *     security={{"ApiKey":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"car_id", "buyer_id", "seller_id", "sale_price"},
 *             @OA\Property(property="car_id", type="integer", example=1),
 *             @OA\Property(property="buyer_id", type="integer", example=2),
 *             @OA\Property(property="seller_id", type="integer", example=1),
 *             @OA\Property(property="sale_price", type="number", format="float", example=25000.00),
 *             @OA\Property(property="payment_method", type="string", enum={"Cash", "Bank Transfer", "Credit Card", "Loan"}, example="Credit Card")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New transaction created"
 *     )
 * )
 */
Flight::route('POST /transactions', function(){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::transactionService()->add_transaction($data));
});

/**
 * @OA\Delete(
 *     path="/transactions/{id}",
 *     tags={"transactions"},
 *     summary="Delete a transaction by ID",
 *     security={{"ApiKey":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Transaction ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Transaction deleted"
 *     )
 * )
 */
Flight::route('DELETE /transactions/@id', function($id){
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::transactionService()->delete_transaction($id));
});

?>