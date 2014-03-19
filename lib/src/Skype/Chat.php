<?php
/**
 * This file is part of Corruption package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 22:01)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skype;

use Skype\TSkype\Proxy;
use Skype\Message;
use Skype;

class Chat
{
    use Proxy;
    protected $proxy = 'chat';
    protected $chat;

    public static function getByName($name)
    {
        return new self(
            Skype::client()->chat($name)
        );
    }

    public static function each(callable $cb, $type = 'recent')
    {
        $name = $type . 'Chats';
        $chats = Skype::client()->$name;
        for ($i = 1, $count = $chats->count->value; $i < $count; $i++) {
            $cb(new self($chats->item($i)));
        }
    }

    public function __construct($chat)
    {
        $this->chat = $chat;
    }

    public function write($message)
    {
        $this->sendMessage($message);
    }

    public function onMessage(callable $cb)
    {
        Skype::client()->events->onMessage(function(Message $message) use ($cb){
            if ($message->getChat()->name == $this->name) {
                $cb($message);
            }
        });
    }

    public function onSend(callable $cb)
    {
        Skype::client()->events->onSend(function(Message $message) use ($cb){
            if ($message->getChat()->name == $this->name) {
                $cb($message);
            }
        });
    }

    public function onAnyMessage(callable $cb)
    {
        Skype::client()->events->onAnyMessage(function(Message $message) use ($cb){
            if ($message->getChat()->name == $this->name) {
                $cb($message);
            }
        });
    }
}