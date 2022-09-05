<?php

namespace BnplPartners\Factoring004VamShop\Handler;

use BnplPartners\Factoring004\ChangeStatus\MerchantsOrders;
use BnplPartners\Factoring004\Exception\ErrorResponseException;
use BnplPartners\Factoring004\Response\ErrorResponse;
use BnplPartners\Factoring004VamShop\Helper\ApiCreationTrait;
use BnplPartners\Factoring004VamShop\Helper\Config;

abstract class AbstractOrderStatusHandler implements OrderStatusHandlerInterface
{
    use ApiCreationTrait;

    final public function handle(array $order, $amount = null)
    {
        $amount = (int) ceil($amount === null ? $order['total'] : $amount);

        if (in_array((string) $order['shipping_method_id'], $this->getConfirmableDeliveryMethods(), true)) {
            $this->sendOtp((string) $order['id'], $amount);
            return true;
        }

        $this->confirmWithoutOtp((string) $order['id'], $amount);
        return false;
    }

    /**
     * @param string $orderId
     * @param int $totalAmount
     *
     * @return void
     *
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    abstract protected function sendOtp($orderId, $totalAmount);

    /**
     * @param string $orderId
     * @param int $totalAmount
     *
     * @return \BnplPartners\Factoring004\ChangeStatus\AbstractMerchantOrder
     */
    abstract protected function createChangeStatusOrder($orderId, $totalAmount);

    /**
     * @param string $orderId
     * @param int $totalAmount
     *
     * @return void
     *
     * @throws \BnplPartners\Factoring004\Exception\PackageException
     */
    protected function confirmWithoutOtp($orderId, $totalAmount)
    {
        $response = $this->createApi()
            ->changeStatus
            ->changeStatusJson([
                new MerchantsOrders($this->getMerchantId(), [
                    $this->createChangeStatusOrder($orderId, $totalAmount),
                ]),
            ]);

        foreach ($response->getErrorResponses() as $errorResponse) {
            throw new ErrorResponseException(new ErrorResponse(
                $errorResponse->getCode(),
                $errorResponse->getMessage(),
                null,
                null,
                $errorResponse->getError()
            ));
        }
    }

    /**
     * @return string[]
     */
    protected function getConfirmableDeliveryMethods()
    {
        $ids = Config::get('factoring004_delivery_methods');

        return $ids ? array_map('trim', explode(',', $ids)) : [];
    }

    /**
     * {@inheritDoc}
     */
    protected function getOAuthToken()
    {
        return Config::get('factoring004_token_as');
    }
}
