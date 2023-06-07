<?php

namespace BnplPartners\Factoring004VamShop\Helper;

use BnplPartners\Factoring004\Api;
use BnplPartners\Factoring004\Auth\BearerTokenAuth;
use BnplPartners\Factoring004\Transport\GuzzleTransport;

trait ApiCreationTrait
{
    /**
     * @return \BnplPartners\Factoring004\Api
     */
    protected function createApi()
    {
        $oauthToken = AuthManager::init(
            Config::get('factoring004_api_host'),
            Config::get('factoring004_login'),
            Config::get('factoring004_password')
        );

        return Api::create(
            Config::get('factoring004_api_host'),
            new BearerTokenAuth($oauthToken->getToken()),
            $this->getTransport()
        );
    }

    /**
     * @return \BnplPartners\Factoring004\Transport\TransportInterface|null
     */
    protected function getTransport()
    {
        $transport = new GuzzleTransport();
        $transport->setLogger(LoggerFactory::create()->createLogger());

        return $transport;
    }

    /**
     * @return string
     */
    protected function getMerchantId()
    {
        return Config::get('factoring004_partner_code');
    }
}
