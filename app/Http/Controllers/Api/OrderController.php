<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\OrderRepository;

class OrderController extends Controller
{

    protected OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderRepository->userPendingOrder();

        if (!$order){
            $order = $this->orderRepository->userPendingOrderCreate();
            $this->orderRepository->userPendingOrderItemCreate($order , $request->product['id']);

            return response()->noContent(200);
        }

        if ($orderItem = $order->orderItems()->where('product_id' , $request->product['id'])->first()){
            $orderItem->update(['quantity' => $orderItem->quantity + 1]);
            return response()->noContent(200);
        }

        $this->orderRepository->userPendingOrderItemCreate($order , $request->product['id']);

        return response()->noContent(200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function orderCheckout(){
        $order = $this->orderRepository->userPendingOrder();

        return response()->json(['order' => $order]);
    }
}
