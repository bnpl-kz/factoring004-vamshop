<?php

namespace BnplPartners\Factoring004VamShop;

use CakeRequest;

class Request extends CakeRequest
{
    /**
     * @var string
     */
    private $method;

    /**
     * @param string $method
     * @param string $url
     * @param bool $parseEnvironment
     */
    public function __construct($method, $url = null, $parseEnvironment = true)
    {
        parent::__construct($url, $parseEnvironment);

        $this->method = $method;
    }

    /**
     * @return string
     */
    public function method()
    {
        return $this->method;
    }
}
