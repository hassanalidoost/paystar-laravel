<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    public function createUnSuccessfulTransaction($data){
        $transaction = Transaction::create([
            'ref_number' => $data['ref_number'],
            'order_id' => $data['order_id'],
            'user_id' => $data['user_id'],
            'status' => 'unsuccessful',
            'transaction_id' => $data['transaction_id'],
            'card_number' => $data['card_number'] ?? null,
            'tracking_code' => $data['tracking_code'] ?? null,
            'message' => $data['message'],
        ]);

        return $transaction;
    }

    public function createSuccessfulTransaction($data){
        $transaction = Transaction::create([
            'ref_number' => $data['ref_number'],
            'order_id' => $data['order_id'],
            'user_id' => $data['user_id'],
            'status' => 'successful',
            'transaction_id' => $data['transaction_id'],
            'card_number' => $data['cardNumber'],
            'tracking_code' => $data['trackingCode'],
            'message' => $data['message'],
        ]);

        return $transaction;
    }
}
