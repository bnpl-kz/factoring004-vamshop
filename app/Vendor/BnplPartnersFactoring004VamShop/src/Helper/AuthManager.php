<?php

namespace BnplPartners\Factoring004VamShop\Helper;

use AdinanCenci\FileCache\Cache;
use BnplPartners\Factoring004\OAuth\CacheOAuthTokenManager;
use BnplPartners\Factoring004\OAuth\OAuthTokenManager;

class AuthManager
{
    private static $authPath = '/users/api/v1';

    private static $cacheKey = 'factoring004';

    private $tokenManager;

    public function __construct($apiHost, $login, $password)
    {
        $cache = new Cache(APP.'/tmp/cache');
        $tokenManager = new OAuthTokenManager($apiHost.self::$authPath, $login, $password);
        $this->tokenManager = new CacheOAuthTokenManager($tokenManager, $cache, self::$cacheKey);
    }

    public static function init($apiHost, $login, $password)
    {
        return new self($apiHost, $login, $password);
    }

    public function getToken()
    {
        return $this->tokenManager->getAccessToken()->getAccess();
    }
}