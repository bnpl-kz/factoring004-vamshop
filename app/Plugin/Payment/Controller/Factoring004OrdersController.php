<?php

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
        parent::admin_new_comment($user);
    }
}
