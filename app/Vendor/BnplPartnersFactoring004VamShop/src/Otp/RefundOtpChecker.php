<?php

namespace BnplPartners\Factoring004VamShop\Otp;

use BnplPartners\Factoring004\Otp\CheckOtpReturn;
use BnplPartners\Factoring004VamShop\Helper\ApiCreationTrait;
use BnplPartners\Factoring004VamShop\Helper\Config;

class RefundOtpChecker implements OtpCheckerInterface
{
    use ApiCreationTrait;

    /**
     * {@inheritDoc}
     */
    public function check($orderId, $amount, $otp)
    {
        $this->createApi()->otp->checkOtpReturn(new CheckOtpReturn($amount, $this->getMerchantId(), $orderId, $otp));
    }
}
