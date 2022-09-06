<?php

namespace BnplPartners\Factoring004VamShop\Handler;

use BnplPartners\Factoring004\ChangeStatus\ReturnStatus;
use BnplPartners\Factoring004VamShop\Helper\Config;

class FullRefundHandler extends AbstractOrderStatusRefundHandler
{
    const KEY = 'full-refund';

    /**
     * {@inheritDoc}
     */
    protected function createReturnStatus()
    {
        return ReturnStatus::RETURN();
    }

    /**
     * {@inheritDoc}
     */
    public function shouldProcess($orderStatusId)
    {
        return $orderStatusId === Config::get('factoring004_return_status');
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return static::KEY;
    }
}
