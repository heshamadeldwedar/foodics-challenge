<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Services\OrderService;

/**
 * @OA\Info(
 *     title="Foodics Challenge",
 *     version="1.0.0"
 * )
 */
class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @OA\Get(
     *     path="/api/orders",
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
     *              @OA\Property(property="id", type="integer", example="1"),
     *              @OA\Property(property="product_id", type="integer", example="1"),
     *              @OA\Property(property="created_at", type="date"),
     *              @OA\Property(property="updated_at", type="date"),
     *              @OA\Property(property="products", type="array",
     *
     *                 @OA\Items(
     *
     *                   @OA\Property(property="id", type="integer", example="1"),
     *                   @OA\Property(property="name", type="string", example="Cheese Burger"),
     *                   @OA\Property(property="created_at", type="date"),
     *                   @OA\Property(property="updated_at", type="date"),
     *                   @OA\Property(property="pivot", type="object",
     *                   @OA\Property(property="order_id", type="integer", example="1"),
     *                   @OA\Property(property="product_id", type="integer", example="1"),
     *                   @OA\Property(property="quantity", type="integer", example="2"),
     *                   @OA\Property(property="created_at", type="date"),
     *                   @OA\Property(property="updated_at", type="date"),
     *               )
     *            )
     *        )
     *      )
     * )))
     */
    public function index()
    {
        return $this->orderService->index();
    }

    /**
     * @OA\Post(
     * path="/api/orders",
     * summary="Create a new order",
     * tags={"Orders"},
     *
     * @OA\RequestBody(
     *   required=true,
     *
     *  @OA\JsonContent(
     *     required={"orders"},
     *
     *    @OA\Property(property="orders", type="array",
     *
     *      @OA\Items(
     *
     *         @OA\Property(property="product_id", type="integer", example="1"),
     *        @OA\Property(property="quantity", type="integer", example="2"),
     *      )
     *   )
     * )
     * ),
     *
     * @OA\Response(
     *    response=200,
     *  description="successful operation",
     *
     * @OA\JsonContent(
     *
     *   @OA\Property(property="id", type="integer"),
     * @OA\Property(property="product_id", type="integer", example="1"),
     * @OA\Property(property="quantity", type="integer", example="2"),
     * @OA\Property(property="created_at", type="date"),
     * @OA\Property(property="updated_at", type="date"),
     * )
     * )
     * )
     */
    public function store(CreateOrderRequest $request)
    {
        return $this->orderService->store($request);
    }
}
