<?php
/* -----------------------------------------------------------------------------------------
   VamShop - http://vamshop.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2014 VamSoft Ltd.
   License - http://vamshop.com/license.html
   ---------------------------------------------------------------------------------------*/
App::uses('PaymentAppController', 'Payment.Controller');

/**
 * @property-read \PaymentMethod $PaymentMethod
 * @property-read \Order $Order
 */
class Factoring004Controller extends PaymentAppController
{
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

    }
}
