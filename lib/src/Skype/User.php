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

class User
{
    use Proxy;
    protected $proxy = 'user';
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function write($msg)
    {
        \Skype::client()
            ->sendMessage($this->handle, $msg);
    }

    public function onMessage(callable $cb)
    {
        throw new EmptyMethodException();
    }

    public function onSend(callable $cb)
    {
        throw new EmptyMethodException();
    }

    public function onAnyMessage(callable $cb)
    {
        throw new EmptyMethodException();
    }
}