<?php

namespace BnplPartners\Factoring004VamShop\Handler;

use BnplPartners\Factoring004VamShop\Exception\OrderStatusHandlerNotFoundException;

class OrderStatusHandlerFactory
{
    const HANDLERS = [
        DeliveryHandler::class,
        FullRefundHandler::class,
    ];

    /**
     * @param string $orderStatusId
     *
     * @return \BnplPartners\Factoring004VamShop\Handler\OrderStatusHandlerInterface
     *
     * @throws \BnplPartners\Factoring004VamShop\Exception\OrderStatusHandlerNotFoundException
     */
    public static function create($orderStatusId)
    {
        /** @var \BnplPartners\Factoring004VamShop\Handler\OrderStatusHandlerInterface $handler */
        foreach (static::HANDLERS as $handlerClass) {
            $handler = new $handlerClass();

            if ($handler->shouldProcess($orderStatusId)) {
                return $handler;
            }
        }

        throw new OrderStatusHandlerNotFoundException("Handler for status {$orderStatusId} not found");
    }
}
