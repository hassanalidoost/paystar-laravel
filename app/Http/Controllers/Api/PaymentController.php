<?php

namespace App\Http\Controllers\Api;

use App\Gateways\Contract\PaymentManager;
use App\Gateways\Paystar;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Order;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentManager;
    protected TransactionRepository $transactionRepository;

    public function __construct(PaymentManager $paymentManager , TransactionRepository $transactionRepository)
    {
        $this->paymentManager = $paymentManager;
        $this->transactionRepository = $transactionRepository;
    }

    public function payment(Request $request)
    {
        $data = [
            'totalAmount' => $request->totalAmount,
            'orderId' => $request->orderId,
        ];

        $response = $this->paymentManager->processPayment('paystar', $data);
        return response()->json($response);
    }

    public function result(Request $request){

        $order = Order::where('id' , $request->order_id)->first();
        $data = [
            'amount' => $order->totalAmount(),
            'ref_number' => $request->ref_number,
            'order_id' => $request->order_id,
            'user_id' => userId(),
            'status' => 'unsuccessful',
            'transaction_id' => $request->transaction_id,
            'cardNumber' => $request->cardNumber,
            'trackingCode' => $request->trackingCode,
            'message' => null
        ];

        if ($request->tracking_code === null){
            //Unsuccessful transaction
            $data['message'] = "Unsuccessful transaction";
            $transaction = $this->transactionRepository->createUnSuccessfulTransaction($data);
            $order->update(['status' => 'unsuccessful']);

            return response()
                ->json([
                    'data' => [
                        'transaction' => $transaction
                    ],
                    'message' => 'Transaction unsuccessful!'
                ], 400);
        }

        if (!in_array($data['cardNumber'] , auth()->user()->cards()->pluck('card_number')->toArray())){
            //Card doesn't match
            $data['message'] = "Card doesn't match";
            $transaction = $this->transactionRepository->createUnSuccessfulTransaction($data);
            $order->update(['status' => 'unsuccessful']);

            return response()
                ->json([
                    'data' => [
                        'transaction' => $transaction
                    ],
                    'message' => 'Transaction unsuccessful!'
                ], 400);
        }

        $response = $this->paymentManager->verify('paystar' , $data);
        if ($response->status == 1){
            //Transaction successful!
            $data['message'] = "Transaction successful!";
            $transaction = $this->transactionRepository->createSuccessfulTransaction($data);
            $order->update(['status' => 'successful']);
            return response()->json([
                'message' => 'Transaction successful!',
                'transaction' => $transaction,
                'verify' => $response,
            ] , 200 );
        }else{
            $data['message'] = "Something else!";
            $transaction = $this->transactionRepository->createUnSuccessfulTransaction($data);
            $order->update(['status' => 'unsuccessful']);
            return response()->json([
                'message' => 'Transaction unsuccessful!',
                'status' => $response->status,
                'verify' => $response,
            ] , 400);
        }
    }

    public function getResult($ref_number){

        $transaction = Transaction::where('ref_number' , $ref_number)->first();
        return response()->json(['transaction' => $transaction] ,200);
    }
}
