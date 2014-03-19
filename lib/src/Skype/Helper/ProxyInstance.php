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

class ProxyInstance
{
    use Proxy;

    protected $instance;
    protected $proxy = 'instance';

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function proxy()
    {
        return $this->instance;
    }
}