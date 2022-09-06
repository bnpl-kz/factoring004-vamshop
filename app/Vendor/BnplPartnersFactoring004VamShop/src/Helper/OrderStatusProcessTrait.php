<?php

namespace BnplPartners\Factoring004VamShop\Helper;

use BnplPartners\Factoring004\Exception\ErrorResponseException;
use BnplPartners\Factoring004VamShop\Exception\OrderStatusHandlerNotFoundException;
use Configure;
use Exception;

/**
 * @mixin \AppController
 * @property-read array<string, mixed> $data
 * @property-read \Psr\Log\LoggerInterface $logger
 */
trait OrderStatusProcessTrait
{
    use SessionTrait;

    /**
     * @param array<string, mixed> $order
     * @param callable(): void $next
     * @param callable(): void $back
     * @param int|null $amount
     *
     * @return void
     */
    protected function process(array $order, $next, $back, $amount = null)
    {
        try {
            $handler = $this->getOrderStatusHandler();

            if (isset($this->data['__otp_checked'])) {
                $this->removeSession($handler->getKey() . '_data');
                $next();
                return;
            }

            $shouldConfirmOtp = $handler->handle($order, $amount);

            if (!$shouldConfirmOtp) {
                $next();
                return;
            }

            $this->putSession($handler->getKey() . '_data', [
                'path' => $this->request->webroot . $this->request->url,
                'data' => $this->data,
                'amount' => $amount ?: $order['total'],
            ]);

            $this->redirect('/factoring004-otp/check/' . $handler->getKey());
        } catch (OrderStatusHandlerNotFoundException $e) {
            $next();
        } catch (ErrorResponseException $e) {
            $response = $e->getErrorResponse();
            $message = $response->getError() . ': ' . $response->getMessage();

            $this->logger->notice($message, $response->toArray());

            $this->Session->setFlash($message);
            $back();
        } catch (Exception $e) {
            $this->logger->error($e);

            $this->Session->setFlash(
                Configure::read('debug') > 0 ? $e->getMessage() : __d('factoring004', 'An error occurred')
            );
            $back();
        }
    }

    /**
     * @return \BnplPartners\Factoring004VamShop\Handler\OrderStatusHandlerInterface
     *
     * @throws \BnplPartners\Factoring004VamShop\Exception\OrderStatusHandlerNotFoundException
     */
    abstract protected function getOrderStatusHandler();
}
