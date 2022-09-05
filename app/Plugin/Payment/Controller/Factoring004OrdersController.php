<?php

use BnplPartners\Factoring004\Exception\ErrorResponseException;
use BnplPartners\Factoring004\Exception\PackageException;
use BnplPartners\Factoring004VamShop\Handler\OrderStatusHandlerFactory;
use BnplPartners\Factoring004VamShop\Helper\LoggerFactory;
use BnplPartners\Factoring004VamShop\Helper\SessionTrait;

App::uses('OrdersController', 'Controller');

require_once ROOT . '/app/Vendor/BnplPartnersFactoring004VamShop/vendor/autoload.php';

/**
 * @property-read \Order $Order
 * @property-read array<string, mixed> $data
 */
class Factoring004OrdersController extends OrdersController
{
    use SessionTrait;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(CakeRequest $request = null, CakeResponse $response = null)
    {
        parent::__construct($request, $response);

        $this->logger = LoggerFactory::create()->createLogger();
    }

    public function admin_new_comment($user = null)
    {
        $order = $this->Order->read([], $this->data['Order']['id']);

        if ($order['PaymentMethod']['alias'] !== 'Factoring004') {
            parent::admin_new_comment($user);
            return;
        }

        if ($this->data['Order']['order_status_id'] === $order['Order']['order_status_id']) {
            parent::admin_new_comment($user);
            return;
        }

        try {
            $handler = OrderStatusHandlerFactory::create($this->data['Order']['order_status_id']);

            if (isset($this->data['__otp_checked'])) {
                $this->removeSession($handler->getKey() . '_data');
                parent::admin_new_comment($user);
                return;
            }

            $shouldConfirmOtp = $handler->handle($order['Order']);

            if ($shouldConfirmOtp) {
                $this->putSession($handler->getKey() . '_data', $this->data);
                $this->redirect('/factoring004-otp/check/' . $handler->getKey());
                return;
            }

            parent::admin_new_comment($user);
        } catch (InvalidArgumentException $e) {
            parent::admin_new_comment($user);
        } catch (ErrorResponseException $e) {
            $response = $e->getErrorResponse();
            $message = $response->getError() . ': ' . $response->getMessage();

            $this->logger->notice($message, $response->toArray());

            $this->Session->setFlash($message);
            $this->redirect('/orders/admin_view/' . $order['Order']['id']);
        } catch (PackageException $e) {
            $this->logger->error($e);

            $this->Session->setFlash(
                Configure::read('debug') > 0 ? $e->getMessage() : __d('factoring004', 'An error occurred')
            );
            $this->redirect('/orders/admin_view/' . $order['Order']['id']);
        }
    }
}
