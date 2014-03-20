<?php
/**
 * This file is part of Corruption package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 21:59)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skype;

use Skype\TSkype\Proxy;
use Skype\Exception\EmptyMethodException;

/**
 * Class User
 * @package Skype
 */
class User
{
    use Proxy;

    /**
     * Com object variable name
     * @var string
     */
    protected $proxy = 'user';

    /**
     * Com user object
     * @var
     */
    protected $user;

    /**
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Send message
     * @param $msg
     */
    public function send($msg)
    {
        \Skype::client()
            ->sendMessage($this->handle, $msg);
    }

    /**
     * Income message events
     * @param callable $cb
     * @throws Exception\EmptyMethodException
     */
    public function onMessage(callable $cb)
    {
        throw new EmptyMethodException();
    }

    /**
     * Send message event
     * @param callable $cb
     * @throws Exception\EmptyMethodException
     */
    public function onSend(callable $cb)
    {
        throw new EmptyMethodException();
    }

    /**
     * Any new message event
     * @param callable $cb
     * @throws Exception\EmptyMethodException
     */
    public function onAnyMessage(callable $cb)
    {
        throw new EmptyMethodException();
    }
}