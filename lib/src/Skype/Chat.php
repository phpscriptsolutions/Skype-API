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

/**
 * Class Chat
 * @package Skype
 */
class Chat
{
    use Proxy;

    /**
     * Proxy name
     * @var string
     */
    protected $proxy = 'chat';

    /**
     * COM object of current skype chat
     * @var
     */
    protected $chat;

    /**
     * Return chat by chat name
     * @param $name
     * @return Chat
     */
    public static function getByName($name)
    {
        return new self(
            Skype::client()->chat($name)
        );
    }

    /**
     * Each chat
     * @param callable $cb
     * @param string $type
     */
    public static function each(callable $cb, $type = 'recent')
    {
        $name = $type . 'Chats';
        $chats = Skype::client()->$name;
        for ($i = 1, $count = $chats->count->value; $i < $count; $i++) {
            $cb(new self($chats->item($i)));
        }
    }

    /**
     * @param $chat
     */
    public function __construct($chat)
    {
        $this->chat = $chat;
    }

    /**
     * Send message inside current chat
     * @param $message
     */
    public function send($message)
    {
        $this->sendMessage($message);
    }

    /**
     * Income message event for current chat
     * @param callable $cb
     */
    public function onMessage(callable $cb)
    {
        Skype::client()->events->onMessage(function(Message $message) use ($cb){
            if ($message->getChat()->name == $this->name) {
                $cb($message);
            }
        });
    }

    /**
     * Send message event for current chat
     * @param callable $cb
     */
    public function onSend(callable $cb)
    {
        Skype::client()->events->onSend(function(Message $message) use ($cb){
            if ($message->getChat()->name == $this->name) {
                $cb($message);
            }
        });
    }

    /**
     * Any new message inside current chat
     * @param callable $cb
     */
    public function onAnyMessage(callable $cb)
    {
        Skype::client()->events->onAnyMessage(function(Message $message) use ($cb){
            if ($message->getChat()->name == $this->name) {
                $cb($message);
            }
        });
    }
}