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

/**
 * Class Timer
 * @package Skype\Helper
 */
class Timer
{
    /**
     * Last callback call timestamp
     * @var int
     */
    private $_lastCheck = 0;

    /**
     * Callback call interval
     * @var int
     */
    private $_interval;

    /**
     * All callbacks
     * @var array
     */
    private $_callbacks = [];

    /**
     * @param int $interval
     */
    public function __construct($interval = 1)
    {
        $this->_interval = $interval;
    }

    /**
     * Add new callback
     * @param callable $cb
     * @return $this
     */
    public function callback(callable $cb)
    {
        $this->_callbacks[] = $cb;
        return $this;
    }

    /**
     * Try call all callbacks
     * @return $this
     */
    public function call()
    {
        if ($this->_lastCheck + $this->_interval < time()) {
            foreach ($this->_callbacks as $cb) { $cb(); }
            $this->_lastCheck = time();
        }
        return $this;
    }

    /**
     * Subscribe on interview Skype\Client event
     * @param Client $client
     */
    public function join(Client $client)
    {
        $client->events->interview(function(){
            $this->call();
        });
    }
}