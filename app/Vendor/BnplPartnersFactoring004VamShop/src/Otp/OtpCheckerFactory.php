<?php

namespace BnplPartners\Factoring004VamShop\Otp;

use InvalidArgumentException;

class OtpCheckerFactory
{
    /**
     * @param string $action
     *
     * @return \BnplPartners\Factoring004VamShop\Otp\OtpCheckerInterface
     *
     * @throws \InvalidArgumentException
     */
    public static function create($action)
    {
        if ($action === 'delivery') {
            return new DeliveryOtpChecker();
        }

        throw new InvalidArgumentException("Otp checker for action {$action} not found");
    }
}
