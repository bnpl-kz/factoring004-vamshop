<?php

namespace BnplPartners\Factoring004VamShop\Handler;

use InvalidArgumentException;

class OrderStatusHandlerFactory
{
    const HANDLERS = [
        DeliveryHandler::class,
    ];

    /**
     * @param string $orderStatusId
     *
     * @return \BnplPartners\Factoring004VamShop\Handler\OrderStatusHandlerInterface
     *
     * @throws \InvalidArgumentException
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

        throw new InvalidArgumentException("Handler for status {$orderStatusId} not found");
    }
}
