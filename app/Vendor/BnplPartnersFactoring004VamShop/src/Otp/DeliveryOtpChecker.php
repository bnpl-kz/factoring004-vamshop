<?php

namespace BnplPartners\Factoring004VamShop\Otp;

use BnplPartners\Factoring004\Otp\CheckOtp;
use BnplPartners\Factoring004VamShop\Helper\ApiCreationTrait;
use BnplPartners\Factoring004VamShop\Helper\Config;

class DeliveryOtpChecker implements OtpCheckerInterface
{
    use ApiCreationTrait;

    /**
     * {@inheritDoc}
     */
    public function check($orderId, $amount, $otp)
    {
        $this->createApi()->otp->checkOtp(new CheckOtp($this->getMerchantId(), $orderId, $otp, $amount));
    }
}
