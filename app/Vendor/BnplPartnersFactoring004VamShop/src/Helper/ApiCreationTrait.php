<?php

namespace BnplPartners\Factoring004VamShop\Helper;

use AdinanCenci\FileCache\Cache;
use BnplPartners\Factoring004\Api;
use BnplPartners\Factoring004\Auth\BearerTokenAuth;
use BnplPartners\Factoring004\OAuth\CacheOAuthTokenManager;
use BnplPartners\Factoring004\OAuth\OAuthTokenManager;
use BnplPartners\Factoring004\Transport\GuzzleTransport;

trait ApiCreationTrait
{
    /**
     * @return \BnplPartners\Factoring004\Api
     */
    protected function createApi()
    {
        $cache = new Cache(APP.'/tmp/cache');
        $tokenManager = new OAuthTokenManager(Config::get('factoring004_api_host').'/users/api/v1',
            Config::get('factoring004_login'),
            Config::get('factoring004_password'));
        $cacheTokenManager = new CacheOAuthTokenManager($tokenManager, $cache, 'factoring004');

        return Api::create(
            Config::get('factoring004_api_host'),
            new BearerTokenAuth($cacheTokenManager->getAccessToken()->getAccess()),
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
