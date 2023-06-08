<?php
/* -----------------------------------------------------------------------------------------
   VamShop - http://vamshop.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2014 VamSoft Ltd.
   License - http://vamshop.com/license.html
   ---------------------------------------------------------------------------------------*/

use BnplPartners\Factoring004\Exception\InvalidSignatureException;
use BnplPartners\Factoring004\Signature\PostLinkSignatureValidator;
use BnplPartners\Factoring004VamShop\Handler\PreAppHandler;
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
    public $uses = ['PaymentMethod', 'Order', 'OrderStatusDescription', 'ShippingMethod'];

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
        $this->set('shippings', $this->getShippingMethods());
        $this->set('statuses', $this->getOrderStatuses());
    }

    /**
     * @return void
     */
    public function install()
    {
        $new_module = array();
        $new_module['PaymentMethod']['active'] = '1';
        $new_module['PaymentMethod']['default'] = '0';
        $new_module['PaymentMethod']['name'] = __d('factoring004', $this->module_name);
        $new_module['PaymentMethod']['description'] = 'Купи сейчас, плати потом! Быстрое и удобное оформление рассрочки на 4 месяца без первоначальной оплаты. Моментальное подтверждение, без комиссий и процентов. Для заказов суммой от 6000 до 200000 тг.';
        $new_module['PaymentMethod']['icon'] = $this->icon;
        $new_module['PaymentMethod']['order'] = 0;
        $new_module['PaymentMethod']['alias'] = $this->module_name;
        $new_module['PaymentMethod']['order_status_id'] = $this->getOrderStatusId('Processing');

        $new_module['PaymentMethodValue'][0]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][0]['key'] = 'factoring004_api_host';
        $new_module['PaymentMethodValue'][0]['value'] = '';

        $new_module['PaymentMethodValue'][1]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][1]['key'] = 'factoring004_login';
        $new_module['PaymentMethodValue'][1]['value'] = '';

        $new_module['PaymentMethodValue'][2]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][2]['key'] = 'factoring004_password';
        $new_module['PaymentMethodValue'][2]['value'] = '';

        $new_module['PaymentMethodValue'][3]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][3]['key'] = 'factoring004_partner_name';
        $new_module['PaymentMethodValue'][3]['value'] = '';

        $new_module['PaymentMethodValue'][4]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][4]['key'] = 'factoring004_partner_code';
        $new_module['PaymentMethodValue'][4]['value'] = '';

        $new_module['PaymentMethodValue'][5]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][5]['key'] = 'factoring004_point_code';
        $new_module['PaymentMethodValue'][5]['value'] = '';

        $new_module['PaymentMethodValue'][6]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][6]['key'] = 'factoring004_partner_email';
        $new_module['PaymentMethodValue'][6]['value'] = '';

        $new_module['PaymentMethodValue'][7]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][7]['key'] = 'factoring004_partner_website';
        $new_module['PaymentMethodValue'][7]['value'] = '';

        $new_module['PaymentMethodValue'][8]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][8]['key'] = 'factoring004_delivery_methods';
        $new_module['PaymentMethodValue'][8]['value'] = '';

        $new_module['PaymentMethodValue'][9]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][9]['key'] = 'factoring004_decline_status';
        $new_module['PaymentMethodValue'][9]['value'] = '';

        $new_module['PaymentMethodValue'][10]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][10]['key'] = 'factoring004_delivery_status';
        $new_module['PaymentMethodValue'][10]['value'] = $this->getOrderStatusId('Delivered');

        $new_module['PaymentMethodValue'][11]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][11]['key'] = 'factoring004_return_status';
        $new_module['PaymentMethodValue'][11]['value'] = '';

        $new_module['PaymentMethodValue'][12]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][12]['key'] = 'factoring004_cancel_status';
        $new_module['PaymentMethodValue'][12]['value'] = '';

        $new_module['PaymentMethodValue'][13]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][13]['key'] = 'factoring004_offer_file';
        $new_module['PaymentMethodValue'][13]['value'] = '';

        $new_module['PaymentMethodValue'][14]['payment_method_id'] = $this->PaymentMethod->id;
        $new_module['PaymentMethodValue'][14]['key'] = 'factoring004_client_route';
        $new_module['PaymentMethodValue'][14]['value'] = '';

        $this->PaymentMethod->saveAll($new_module);
        $this->Session->setFlash(__('Module Installed'));
        $this->appendDataCheckoutFile();
        $this->redirect('/payment_methods/admin/');
    }

    /**
     * @return void
     */
    public function uninstall()
    {
        $module_id = $this->PaymentMethod->findByAlias($this->module_name);

        $this->PaymentMethod->delete($module_id['PaymentMethod']['id'], true);

        $this->Session->setFlash(__('Module Uninstalled'));
        $this->redirect('/payment_methods/admin/');
    }

    /**
     * @return string|null
     */
    public function before_process()
    {
        $content = '';

        try {
            $preApp = new PreAppHandler();

            $preAppLink = $preApp->preApp();

            if (Config::get('factoring004_client_route') === 'modal') {
                $domain = stripos(Config::get('factoring004_api_host'), 'dev') ? 'dev.bnpl.kz' : 'bnpl.kz';
                $content .= "<button id='factoring004-open-modal' class='btn btn-default' type='button'>{lang}Process to Payment{/lang}</button>
                    <script defer src='https://$domain/widget/index_bundle.js'></script>
                    <div id='modal-factoring004'></div>
                    <script>
                        jQuery(function($) {
                            $(document).on('click', '#factoring004-open-modal', function () {
                                const bnplKzApi = new BnplKzApi.CPO({
                                      rootId: 'modal-factoring004',
                                      callbacks: {
                                        onError: () => window.location.replace('$preAppLink'),
                                        onDeclined: () => window.location.replace('/'),
                                        onEnd: () => window.location.replace('/'),
                                        onClosed: () => window.location.reload()
                                      }
                                    });
                                    bnplKzApi.render({
                                        redirectLink: '$preAppLink'
                                    });
                            })
                        })
                    </script>
                ";
            } else {
                $content .= '<a class="btn btn-default" href="'.$preAppLink.'">{lang}Process to Payment{/lang}</a>';
            }

            return $content;

        } catch (Exception $e) {
            $this->redirect(Router::url('error'));
        }
    }

    public function error()
    {
        $this->layout = '';
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

    /**
     * @param $name
     * @return mixed
     */
    private function getOrderStatusId($name)
    {
        return $this->OrderStatusDescription->field('order_status_id', ['name'=>$name]);
    }

    /**
     * @return mixed
     */
    private function getShippingMethods()
    {
        return $this->ShippingMethod->find('list', ['conditions' => ['active' => '1']]);
    }

    /**
     * @return mixed
     */
    private function getOrderStatuses()
    {
        return $this->OrderStatusDescription->find('list',['conditions' => ['language_id' => $this->Session->read('Customer.language_id')]]);
    }

    private function appendDataCheckoutFile()
    {
        $file = file_get_contents(ROOT . '/app/Catalog/function.checkout.php');

        if (!strripos($file, '<?php require_once "factoring004-checkout.php"; ?>')) {
            file_put_contents(
                ROOT . '/app/Catalog/function.checkout.php', '<?php require_once "factoring004-checkout.php"; ?>',
                FILE_APPEND | LOCK_EX
            );
        }
    }
}
