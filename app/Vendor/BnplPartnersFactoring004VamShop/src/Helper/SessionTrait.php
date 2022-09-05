<?php

namespace BnplPartners\Factoring004VamShop\Helper;

/**
 * @mixin \AppController
 */
trait SessionTrait
{
    /**
     * @param string $key
     *
     * @return bool
     */
    protected function hasSession($key)
    {
        return $this->Session->check('Factoring004_' . $key);
    }
    
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    protected function putSession($key, $value)
    {
        $this->Session->write('Factoring004_' . $key, $value);
    }

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    protected function getSession($key, $default = null)
    {
        $value = $this->Session->read('Factoring004_' . $key);

        return $value === null ? $default : $value;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    protected function removeSession($key)
    {
        $this->Session->delete('Factoring004_' . $key);
    }

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    protected function pullSession($key, $default = null)
    {
        $value = $this->Session->consume($key);

        return $value === null ? $default : $value;
    }
}
