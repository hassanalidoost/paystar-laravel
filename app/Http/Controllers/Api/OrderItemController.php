<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;

class OrderItemController extends Controller
{
    public function increaseQuantity($id)
    {
        $item = OrderItem::findOrFail($id);
        $item->update(['quantity' => $item->quantity + 1]);
        return response()->noContent(200);
    }

    public function decreaseQuantity($id){
        $item = OrderItem::findOrFail($id);
        if ($item->quantity === 1 ){
            $this->destroy($id);
        }
        $item->update(['quantity' => $item->quantity - 1]);
        return response()->noContent(200);
    }

    public function destroy($id){
        $item = OrderItem::with('order')->findOrFail($id);
        $item->delete();

        if (!$item->order->orderItems->count()){
            $item->order()->delete();
        }

        return response()->noContent(200);
    }
}
