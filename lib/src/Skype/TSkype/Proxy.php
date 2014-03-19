<?php
/**
 * This file is part of Corruption package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 20:30)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skype\TSkype;

use Exception;
use Skype\Helper\ProxyInstance;

/**
 * Class Proxy
 * @package Skype\TSkype
 */
trait Proxy
{
    /**
     * @return Proxy
     * @throws \Exception
     */
    protected function target()
    {
        if (!isset($this->proxy)) {
            throw new Exception('Undefined proxy name');
        }
        $name = $this->proxy;
        return $this->$name;
    }

    /**
     * @param $name
     * @return string
     */
    protected static function normalize($name)
    {
        return ucfirst($name);
    }

    /**
     * @param $v
     * @return mixed
     */
    public function __invoke($v)
    {
        return $this->target()->__toString();
    }

    /**
     * @param $foo
     * @param $args
     * @return Proxy
     */
    public function __call($foo, $args = [])
    {
        $convArgs = [];
        foreach ($args as $arg) {
            $convArgs[] = \Skype::decode($arg);
        }

        $name = self::normalize($foo);
        return new ProxyInstance(
            (count($args) > 0)
                ? call_user_func_array([$this->target(), $name], $convArgs)
                : $this->target()->$name
        );
    }

    /**
     * @param $name
     * @return Proxy
     */
    public function __get($name)
    {
        if ($name == 'value') { return (string)$this; }

        $name = self::normalize($name);
        return new ProxyInstance(
            $this->target()->$name
        );
    }

    /**
     * @param $name
     * @param $val
     * @return mixed
     */
    public function __set($name, $val)
    {
        return $this->target()->$name = \Skype::decode($val);
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return \Skype::encode(
            print_r($this->target(), 1)
        );
    }

    /**
     * @return string
     */
    public function toString()
    {
        return (string)$this;
    }
}