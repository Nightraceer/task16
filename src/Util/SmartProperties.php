<?php

namespace Main\Util;

/**
 * Trait SmartProperties
 *
 * Доступ к сеттерам и геттерам объекта как к свойствам
 *
 * @package Main\Util
 */
trait SmartProperties
{
    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->smartGet($name);
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        return $this->smartSet($name, $value);
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function smartGet($name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            throw new \Exception('Unknown property ' . $name);
        }
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function smartSet($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        } else {
            throw new \Exception('Unknown property ' . $name);
        }
    }
}