<?php

namespace BnplPartners\Factoring004VamShop\Otp;

use BnplPartners\Factoring004VamShop\Exception\OtpCheckerNotFoundException;

class OtpCheckerFactory
{
    /**
     * @param string $action
     *
     * @return \BnplPartners\Factoring004VamShop\Otp\OtpCheckerInterface
     *
     * @throws \BnplPartners\Factoring004VamShop\Exception\OtpCheckerNotFoundException
     */
    public static function create($action)
    {
        if ($action === 'delivery') {
            return new DeliveryOtpChecker();
        }

        throw new OtpCheckerNotFoundException("Otp checker for action {$action} not found");
    }
}
