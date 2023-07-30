<?php

namespace App\Gateways\Contract;

interface PaymentGatewayInterface
{
    public function processPayment(array $data);
    public function verify(array $data);
}
