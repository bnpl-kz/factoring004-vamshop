<?php

namespace BnplPartners\Factoring004VamShop\Helper;

use CakeLog;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use ReflectionClass;

class Logger extends AbstractLogger
{
    /**
     * @var string[]
     */
    private $levels;

    /**
     * @param string $level
     */
    public function __construct($level = LogLevel::DEBUG)
    {
        $this->levels = $this->getWritableLevels($level);
    }

    public function log($level, $message, array $context = [])
    {
        if (in_array($level, $this->levels, true)) {
            return;
        }

        CakeLog::write($level, $message);
    }

    /**
     * @param string $level
     *
     * @return string[]
     */
    private function getWritableLevels($level)
    {
        $class = new ReflectionClass(LogLevel::class);
        $levels = array_reverse(array_values($class->getConstants()));

        return array_slice($levels, array_search($level, $levels, true));
    }
}
