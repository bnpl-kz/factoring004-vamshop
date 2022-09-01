<?php
/* -----------------------------------------------------------------------------------------
   VamShop - http://vamshop.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2014 VamSoft Ltd.
   License - http://vamshop.com/license.html
   ---------------------------------------------------------------------------------------*/

use BnplPartners\Factoring004\Exception\InvalidSignatureException;
use BnplPartners\Factoring004\Signature\PostLinkSignatureValidator;
use BnplPartners\Factoring004VamShop\Helper\Config;
use BnplPartners\Factoring004VamShop\Helper\LoggerFactory;

App::uses('PaymentAppController', 'Payment.Controller');

require_once ROOT . '/app/Vendor/BnplPartnersFactoring004VamShop/vendor/autoload.php';

/**
 * @property-read \PaymentMethod $PaymentMethod
 * @property-read \Order $Order
 */
class Factoring004Controller extends PaymentAppController
{
    const POST_LINK_REQUIRED_FIELDS = ['status', 'billNumber', 'preappId'];
    const STATUS_PREAPPROVED = 'preapproved';
    const STATUS_DECLINED = 'declined';
    const STATUS_COMPLETED = 'completed';
    const POST_LINK_RESPONSE_OK = 'ok';

    /**
     * @var string[]
     */
    public $uses = ['PaymentMethod', 'Order'];

    /**
     * @var string
     */
    public $module_name = 'Factoring004';

    /**
     * @var string
     */
    public $icon = 'factoring004.png';

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
    public function settings()
    {
        $this->set('data', $this->PaymentMethod->findByAlias($this->module_name));
    }

    /**
     * @return void
     */
    public function install()
    {

    }

    /**
     * @return void
     */
    public function uninstall()
    {

    }

    /**
     * @return string|null
     */
    public function before_process()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function after_process()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function payment_after()
    {
        return null;
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    public function result()
    {
        $this->autoRender = false;

        $this->logger->debug('Factoring004 POSTLINK: ' . $this->request->input());

        $this->validateInput();

        $status = $this->request->data('status');
        $billNumber = $this->request->data('billNumber');

        $order = $this->Order->read([], $billNumber);

        if (!$order) {
            throw new BadRequestException("Order {$billNumber} not found");
        }

        if ($order['PaymentMethod']['alias'] !== $this->module_name) {
            throw new BadRequestException('Order payment is not factoring004');
        }

        if ($status === static::STATUS_PREAPPROVED) {
            $this->jsonResponse(['response' => $status]);
            return;
        }

        if ($status === static::STATUS_COMPLETED) {
            $order['Order']['order_status_id'] = $order['PaymentMethod']['order_status_id'];
        } elseif ($status === static::STATUS_DECLINED) {
            $order['Order']['order_status_id'] = Config::get('factoring004_decline_status');
        } else {
            throw new BadRequestException('Unsupported status ' . $status);
        }

        $this->Order->save($order);

        $this->jsonResponse(['response' => static::POST_LINK_RESPONSE_OK]);
    }

    /**
     * @return void
     *
     * @throws \BadRequestException
     */
    private function validateInput()
    {
        foreach (static::POST_LINK_REQUIRED_FIELDS as $field) {
            if (!is_string($this->request->data($field))) {
                throw new BadRequestException("Field {$field} is required and must be a string");
            }
        }

        $this->validateSignature();
    }

    /**
     * @return void
     *
     * @throws \BadRequestException
     */
    private function validateSignature()
    {
        try {
            PostLinkSignatureValidator::create(Config::get('factoring004_partner_code'))
                ->validateData($this->request->data);
        } catch (InvalidSignatureException $e) {
            throw new BadRequestException($e->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param int $status
     * @param int $flags
     *
     * @return void
     */
    private function jsonResponse(array $data, $status = 200, $flags = 0)
    {
        $json = json_encode($data, $flags);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new UnexpectedValueException(json_last_error_msg(), json_last_error());
        }

        $this->response->statusCode($status);
        $this->response->type('json');
        $this->response->body($json);
    }
}
