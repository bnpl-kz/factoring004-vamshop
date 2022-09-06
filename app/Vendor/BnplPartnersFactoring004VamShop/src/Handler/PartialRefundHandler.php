<?php

namespace BnplPartners\Factoring004VamShop\Handler;

use BnplPartners\Factoring004\ChangeStatus\ReturnStatus;

class PartialRefundHandler extends AbstractOrderStatusRefundHandler
{
    const KEY = 'partial-refund';

    /**
     * {@inheritDoc}
     */
    protected function createReturnStatus()
    {
        return ReturnStatus::PARTRETURN();
    }

    /**
     * {@inheritDoc}
     */
    public function shouldProcess($orderStatusId)
    {
        return (string) $orderStatusId === static::KEY;
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return static::KEY;
    }
}
