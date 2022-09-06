<?php

use BnplPartners\Factoring004VamShop\Handler\OrderStatusHandlerFactory;
use BnplPartners\Factoring004VamShop\Helper\LoggerFactory;
use BnplPartners\Factoring004VamShop\Helper\OrderStatusProcessTrait;

App::uses('OrdersController', 'Controller');

require_once ROOT . '/app/Vendor/BnplPartnersFactoring004VamShop/vendor/autoload.php';

/**
 * @property-read \Order $Order
 * @property-read array<string, mixed> $data
 */
class Factoring004OrdersController extends OrdersController
{
    use OrderStatusProcessTrait;

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

        $this->process(
            $order['Order'],
            function () use ($user) {
                parent::admin_new_comment($user);
            },
            function () use ($order) {
                $this->redirect('/orders/admin_view/' . $order['Order']['id']);
            }
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function getOrderStatusHandler()
    {
        return OrderStatusHandlerFactory::create($this->data['Order']['order_status_id']);
    }
}
