<?php
/**
 * This file is part of Skype package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 22:46)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skype\Helper;

use Skype\Client;

class Timer
{
    private $_lastCheck = 0;
    private $_interval;
    private $_callbacks = [];

    public function __construct($interval = 1)
    {
        $this->_interval = $interval;
    }

    public function callback(callable $cb)
    {
        $this->_callbacks[] = $cb;
        return $this;
    }

    public function call()
    {
        if ($this->_lastCheck + $this->_interval < time()) {
            foreach ($this->_callbacks as $cb) { $cb(); }
            $this->_lastCheck = time();
        }
        return $this;
    }

    public function join(Client $client)
    {
        $client->events->interview(function(){
            $this->call();
        });
    }
}