<?php

namespace App\Gateways;

use App\Gateways\Contract\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;

class Paystar implements PaymentGatewayInterface
{
    protected const CALLBACK = "http://localhost:5173/callback";

    public function create($orderID , $totalAmount ){
        $callback = self::CALLBACK;
        $sign = $this->sign("$totalAmount#$orderID#$callback" , config('paystar.encoding_key'));
        $token = config('paystar.token');
        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json',
        ])
        ->post('https://core.paystar.ir/api/pardakht/create' , [
            'amount' => $totalAmount,
            'order_id' => $orderID,
            'callback' => self::CALLBACK,
            'sign' => $sign,
            'callback_method' => 1,
        ]);

        return $response->successful() ? $response->json() : false;
    }

    public function sign($string, $secretKey ){
        $hash = hash_hmac('sha512', $string , $secretKey);

        return $hash;
    }

    public function payment($token) {
        $path = config('paystar.api_url') . '/payment' . '?' . $token;
        return redirect($path);
    }

    public function verify($data){
        $amount = $data['amount'];
        $ref_number = $data['ref_number'];
        $cardNumber = $data['cardNumber'];
        $trackingCode = $data['trackingCode'];

        $sign = $this->sign("$amount#$ref_number#$cardNumber#$trackingCode" , config('paystar.encoding_key'));
        $token = config('paystar.token');
        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json',
        ])
        ->post('https://core.paystar.ir/api/pardakht/verify' , [
            'ref_num' => $ref_number,
            'amount' => $amount,
            'sign' => $sign,
        ]);

        return json_decode($response->body());
    }

    public function processPayment(array $data)
    {
        $createRes = $this->create($data['orderId'] , $data['totalAmount']);

        if (!$createRes){
            return response()->json(['data' => '' , 'message' => 'Something went wrong!'] , 500);
        }

        return $createRes;
    }


}
