<?php

namespace BnplPartners\Factoring004VamShop\Handler;

use BnplPartners\Factoring004\PreApp\PreAppMessage;
use BnplPartners\Factoring004VamShop\Helper\ApiCreationTrait;
use BnplPartners\Factoring004VamShop\Helper\Config;
use ContentDescription;
use Router;

class PreAppHandler
{
    use ApiCreationTrait;

    /**
     * @var ContentDescription $contentDescription
     */
    private $contentDescription;

    public function __construct()
    {
        $this->contentDescription = new ContentDescription();
    }

    public function preApp()
    {
        return $this->createApi()->preApps->preApp($this->preAppMessage())->getRedirectLink();
    }

    protected function getOAuthToken()
    {
        return Config::get('factoring004_token_bp');
    }

    private function preAppMessage()
    {
        global $order;

        return PreAppMessage::createFromArray([
            'partnerData' => [
                'partnerName' => Config::get('factoring004_partner_name'),
                'partnerCode' => Config::get('factoring004_partner_code'),
                'pointCode' => Config::get('factoring004_point_code'),
                'partnerEmail' => Config::get('factoring004_partner_email'),
                'partnerWebsite' => Config::get('factoring004_partner_website'),
            ],
            'phoneNumber'=> $order['Order']['phone']
                ? '7' . str_replace(["(",")","-"," "], '', $order['Order']['phone'])
                : null,
            'billNumber' => (string) $order['Order']['id'],
            'billAmount' => (int) round($order['Order']['total']),
            'itemsQuantity' => (int) array_sum(array_map(function ($item) {
                return $item['quantity'];
            }, $order['OrderProduct'])),
            'successRedirect' => 'http'.(isset($_SERVER['HTTPS']) ? "s" : '').'://'.$_SERVER['HTTP_HOST'],
            'failRedirect' => 'http'.(isset($_SERVER['HTTPS']) ? "s" : '').'://'.$_SERVER['HTTP_HOST'],
            'postLink' => 'http'.(isset($_SERVER['HTTPS']) ? "s" : '').'://'.$_SERVER['HTTP_HOST'].'/'.Router::url('result'),
            'items' => array_values(array_map(function ($item) {
                return [
                    'itemId' => (string) $item['id'],
                    'itemName' => (string) $item['name'],
                    'itemCategory' => (string) $this->contentDescription
                        ->find(
                            'first', ['fields' => ['name'],
                                'conditions' => [
                                    'content_id'=>$item['order_id'],
                                    'language_id'=>$_SESSION['Customer']['language_id']
                                ]]
                        ),
                    'itemQuantity' => (int) $item['quantity'],
                    'itemPrice' => (int) ceil($item['price']),
                    'itemSum' => (int) ceil($item['price'] * $item['quantity']),
                ];
            }, $order['OrderProduct'])),
            'deliveryPoint'=> [
                'city'=> $order['Order']['bill_city'],
                'street'=> $order['Order']['bill_line_1'] . $order['Order']['bill_line_2']
            ]
        ]);
    }
}