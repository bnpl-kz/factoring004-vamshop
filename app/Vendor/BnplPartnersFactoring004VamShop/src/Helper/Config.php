<?php

namespace BnplPartners\Factoring004VamShop\Helper;

use PaymentMethod;

\App::import('Model', 'PaymentMethod');

class Config
{
    /**
     * @var array<string, mixed>
     */
    private static $config = [];

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $items = static::all();

        return isset($items[$key]) ? $items[$key] : $default;
    }

    /**
     * @return array|null
     */
    public static function getPayment()
    {
        return (new PaymentMethod())->find('first', ['conditions' => ['alias' => 'Factoring004']]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function all()
    {
        static::load();

        return static::$config;
    }

    /**
     * @return void
     */
    private static function load()
    {
        if (static::$config) {
            return;
        }

        static::$config = static::fetch();
    }

    /**
     * @return array<string, mixed>
     */
    private static function fetch()
    {
        $model = new PaymentMethod();
        $items = $model->find('first', ['conditions' => ['alias' => 'Factoring004'], 'fields' => ['id', 'order_status_id']]);
        $result = [];

        if (isset($items['PaymentMethod']['order_status_id'])) {
            $result['factoring004_paid_status'] = $items['PaymentMethod']['order_status_id'];
        }

        if (isset($items['PaymentMethodValue'])) {
            foreach ($items['PaymentMethodValue'] as $item) {
                $result[$item['key']] = $item['value'];
            }
        }

        return $result;
    }
}
