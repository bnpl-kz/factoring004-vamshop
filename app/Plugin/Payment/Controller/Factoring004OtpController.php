<?php

use BnplPartners\Factoring004VamShop\Helper\LoggerFactory;
use BnplPartners\Factoring004VamShop\Helper\SessionTrait;

App::uses('AppController', 'Controller');

require_once ROOT . '/app/Vendor/BnplPartnersFactoring004VamShop/vendor/autoload.php';

/**
 * @property-read \Order $Order
 */
class Factoring004OtpController extends AppController
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

    /**
     * @return void
     */
    public function index()
    {
        $this->set('current_crumb', __d('factoring004', 'Check') . ' ' . __d('factoring004', 'OTP'));
        $this->set('title_for_layout', __d('factoring004', 'Check') . ' ' . __d('factoring004', 'OTP'));
    }

    /**
     * @param string $type
     *
     * @return void
     */
    public function check($type)
    {

    }
}
