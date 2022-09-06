<?php

use BnplPartners\Factoring004VamShop\Handler\PartialRefundHandler;
use BnplPartners\Factoring004VamShop\Helper\LoggerFactory;
use BnplPartners\Factoring004VamShop\Helper\OrderStatusProcessTrait;

App::uses('OrdersEditController', 'Controller');

require_once ROOT . '/app/Vendor/BnplPartnersFactoring004VamShop/vendor/autoload.php';

/**
 * @property-read array<string, mixed> $data
 * @property-read \Order $Order
 */
class Factoring004OrdersEditController extends OrdersEditController
{
    use OrderStatusProcessTrait;

    /**
     * @var string[]
     */
    public $uses = ['Order'];

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(CakeRequest $request = null, CakeResponse $response = null)
    {
        parent::__construct($request, $response);

        $this->logger = LoggerFactory::create()->createLogger();
    }

    public function save_order()
    {
        $tmpOrder = $this->Session->read('order_edit.order');
        $order = $this->Order->read([], $tmpOrder['id']);

        if ($order['PaymentMethod']['alias'] !== 'Factoring004') {
            parent::save_order();
            return;
        }

        $orderId = $order['Order']['id'];
        $orderTotal = $order['Order']['total'];
        $tmpTotal = array_reduce($tmpOrder['OrderProduct'], function ($prev, array $current) {
            return $prev + $current['price'] * $current['quantity'];
        }, 0);

        if ($tmpTotal > $orderTotal) {
            $this->Session->setFlash(sprintf(
                '%s. %s',
                __d('factoring004', 'The order amount was increased'),
                __d('factoring004', 'Please full refund the order and create new')
            ));
            $this->redirect('/orders_edit/admin/edit/' . $tmpOrder['id']);
            return;
        }

        $tmpProductIds = array_map(function (array $item) {
            return $item['content_id'];
        }, $tmpOrder['OrderProduct']);

        $productIds = array_map(function (array $item) {
            return $item['content_id'];
        }, $order['OrderProduct']);

        sort($tmpProductIds);
        sort($productIds);

        if ($tmpTotal < $orderTotal) {
            if (!$tmpProductIds) {
                $this->Session->setFlash(
                    __d('factoring004', 'At least one product should remain into the order')
                );

                $this->redirect('/orders_edit/admin/edit/' . $tmpOrder['id']);
                return;
            }

            foreach ($tmpProductIds as $tmpProductId) {
                if (!in_array($tmpProductId, $productIds, true)) {
                    $this->Session->setFlash(sprintf(
                        '%s. %s',
                        __d('factoring004', 'You are trying to add new products into the order'),
                        __d('factoring004', 'Please full refund the order and create new')
                    ));

                    return;
                }
            }

            $this->data = array_merge($this->data, ['Order' => $tmpOrder]);
            $this->process(
                $order['Order'],
                function () {
                    parent::save_order();
                },
                function () use ($orderId) {
                    $this->redirect('/orders_edit/admin/edit/' . $orderId);
                },
                $tmpTotal
            );
            return;
        }

        if ($tmpProductIds === $productIds) {
            parent::save_order();
            return;
        }

        $this->Session->setFlash(sprintf(
            '%s. %s',
            __d('factoring004', 'You are trying to add new products into the order'),
            __d('factoring004', 'Please full refund the order and create new')
        ));
    }

    /**
     * {@inheritDoc}
     */
    protected function getOrderStatusHandler()
    {
        return new PartialRefundHandler();
    }
}
