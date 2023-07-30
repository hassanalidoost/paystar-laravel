<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function userPendingOrder()
    {
        return Order::with('orderItems.product' , 'orderItems.order')
            ->where('user_id', userId())
            ->where('status', 'pending')
            ->first();
    }

    public function userPendingOrderCreate()
    {
        return Order::create([
            'status' => 'pending',
            'user_id' => userId()
        ]);
    }

    public function userPendingOrderItemCreate($order, $prodId)
    {
        $order->orderItems()->create([
            'product_id' => $prodId,
            'quantity' => 1,
        ]);
    }
}
