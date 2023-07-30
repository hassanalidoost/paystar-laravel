<?php

namespace App\Gateways\Contract;

use App\Gateways\Paystar;
use InvalidArgumentException;

class PaymentManager
{
    protected $gateways = [
        'paystar' => Paystar::class,
        // Add more gateway names and classes here
    ];

    public function processPayment($gatewayName, array $data)
    {
        if (!isset($this->gateways[$gatewayName])) {
            throw new InvalidArgumentException('Invalid gateway');
        }

        $gatewayClass = $this->gateways[$gatewayName];
        /** @var PaymentGatewayInterface $gateway */
        $gateway = new $gatewayClass();

        return $gateway->processPayment($data);
    }

    public function verify($gatewayName, array $data)
    {
        if (!isset($this->gateways[$gatewayName])) {
            throw new InvalidArgumentException('Invalid gateway');
        }

        $gatewayClass = $this->gateways[$gatewayName];
        /** @var PaymentGatewayInterface $gateway */
        $gateway = new $gatewayClass();

        return $gateway->verify($data);
    }
}
