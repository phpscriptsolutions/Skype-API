<?php
/**
 * This file is part of Corruption package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 20:55)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skype\Helper;

use Skype\TSkype\Proxy;

/**
 * Class ProxyInstance
 * @package Skype\Helper
 */
final class ProxyInstance
{
    use Proxy;

    /**
     * COM variable
     * @var
     */
    protected $instance;

    /**
     * Variable name
     * @var string
     */
    protected $proxy = 'instance';

    /**
     * @param $instance
     */
    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    /**
     * Return clear COM variable
     * @return mixed
     */
    public function proxy()
    {
        return $this->instance;
    }
}