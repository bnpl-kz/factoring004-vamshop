<?php

use BnplPartners\Factoring004\Exception\ErrorResponseException;
use BnplPartners\Factoring004\Exception\PackageException;
use BnplPartners\Factoring004VamShop\Helper\LoggerFactory;
use BnplPartners\Factoring004VamShop\Helper\SessionTrait;
use BnplPartners\Factoring004VamShop\Otp\OtpCheckerFactory;
use BnplPartners\Factoring004VamShop\Request;

App::uses('AppController', 'Controller');
App::uses('Dispatcher', 'Routing');

require_once ROOT . '/app/Vendor/BnplPartnersFactoring004VamShop/vendor/autoload.php';

/**
 * @property-read \Order $Order
 */
class Factoring004OtpController extends AppController
{
    use SessionTrait;

    const OTP_CHECK_PATH = '/factoring004-otp/check/';
    const FORWARD_PATH = '/orders/admin_new_comment/';

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
        $otp = $this->request->data('false.otp');

        if (!is_string($otp) || !preg_match('/^\d{4}$/', $otp)) {
            $this->Session->setFlash(__d('factoring004', 'Invalid OTP code'));
            $this->redirect(static::OTP_CHECK_PATH . $type);
            return;
        }

        $data = $this->getSession($type . '_data');

        if (!$data) {
            $this->Session->setFlash(
                __d('factoring004', 'Session expired. Please back to previous page and update order status again')
            );
            $this->redirect(static::OTP_CHECK_PATH . $type);
            return;
        }

        $orderId = $data['Order']['id'];
        $amount = $this->Order->field('total', ['id' => $orderId]);

        try {
            OtpCheckerFactory::create($type)->check($orderId, $amount, $otp);

            $data['__otp_checked'] = true;
            $this->forwardToController($data);
            exit;
        } catch (ErrorResponseException $e) {
            $response = $e->getErrorResponse();
            $message = $response->getError() . ': ' . $response->getMessage();

            $this->logger->notice($message, $response->toArray());

            $this->Session->setFlash($message);
            $this->redirect(static::OTP_CHECK_PATH . $type);
        } catch (PackageException $e) {
            $this->logger->error($e);

            $this->Session->setFlash(
                Configure::read('debug') > 0 ? $e->getMessage() : __d('factoring004', 'An error occurred')
            );
            $this->redirect(static::OTP_CHECK_PATH . $type);
        }
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return void
     */
    private function forwardToController(array $data)
    {
        $request = new Request('POST', static::FORWARD_PATH, false);
        $request->data = $data;

        $dispatcher = new Dispatcher();
        $dispatcher->dispatch($request, $this->response);
    }
}
