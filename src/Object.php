<?php

namespace m8rge\curl;


class Object
{
    /**
     * Object constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->setConfig($config);
        $this->init();
    }

    public function init()
    {

    }

    /**
     * @param array $config
     */
    public function setConfig($config = [])
    {
        if (!empty($config)) {
            foreach ($config as $name => $value) {
                $this->__set($name, $value);
            }
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } else {
            $this->$name = $value;
        }
    }
}