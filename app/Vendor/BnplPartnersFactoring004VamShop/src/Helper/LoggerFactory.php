<?php

namespace BnplPartners\Factoring004VamShop\Helper;

class LoggerFactory
{
    /**
     * @return \BnplPartners\Factoring004VamShop\Helper\LoggerFactory
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function createLogger()
    {
        return new Logger();
    }
}