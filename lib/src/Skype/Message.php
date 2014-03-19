<?php
/**
 * This file is part of Corruption package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 21:26)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skype;

use Skype;
use Skype\User;
use Skype\Chat;
use Skype\TSkype\Proxy;

class Message
{
    use Proxy;
    protected $proxy = 'message';
    protected $message;

    public $id;
    public $guid;
    public $timestamp;
    public $body;


    public function __construct($message)
    {
        $this->message     = $message;
        $this->id           = $message->id;
        $this->guid         = $message->guid;
        $this->timestamp    = $message->timestamp;
        $this->body         = Skype::encode($message->Body);
    }

    public function like($msg)
    {
        return mb_strtolower(trim($this->body)) == mb_strtolower(trim($msg));
    }

    public function answer($message)
    {
        $this->getChat()->write($message);
    }

    public function getUser()
    {
        return new User(
            $this->message->sender
        );
    }

    public function getChat()
    {
        return new Chat(
            $this->message->chat
        );
    }

}