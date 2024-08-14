<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Foodics Challenge",
 *     version="1.0.0"
 * )
 */
class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/orders",
     *     summary="get all orders",
     *     tags={"Orders"},
     *
     *    @OA\Response(
     *        response=200,
     *       description="successful operation",
     *
     *      @OA\JsonContent(
     *          type="array",
     *
     *         @OA\Items(
     *
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="product_id", type="integer"),
     *              @OA\Property(property="user_id", type="integer"),
     *              @OA\Property(property="created_at", type="date"),
     *              @OA\Property(property="updated_at", type="date"),
     *          )
     *      )
     *    )
     * )
     */
    public function index()
    {
        return Order::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderRequest $request)
    {
        return $request;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
