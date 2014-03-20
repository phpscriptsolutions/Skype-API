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

/**
 * Class Message
 * @package Skype
 */
class Message
{
    use Proxy;

    /**
     * Com object variable name
     * @var string
     */
    protected $proxy = 'message';

    /**
     * Com message object
     * @var
     */
    protected $message;

    /**
     * Message id
     * @var
     */
    public $id;

    /**
     * Message guid
     * @var
     */
    public $guid;

    /**
     * Message timestamp
     * @var
     */
    public $timestamp;

    /**
     * Message content
     * @var string
     */
    public $body;

    /**
     * @param $message
     */
    public function __construct($message)
    {
        $this->message     = $message;
        $this->id           = $message->id;
        $this->guid         = $message->guid;
        $this->timestamp    = $message->timestamp;
        $this->body         = Skype::encode($message->Body);
    }

    /**
     * Message body compatible
     * @param $msg
     * @return bool
     */
    public function like($msg)
    {
        return mb_strtolower(trim($this->body)) == mb_strtolower(trim($msg));
    }

    /**
     * Answer on message
     * @param $message
     */
    public function answer($message)
    {
        $this->getChat()->send($message);
    }

    /**
     * Return sender of this message
     * @return User
     */
    public function getUser()
    {
        return new User(
            $this->message->sender
        );
    }

    /**
     * Return chat object of this message
     * @return Chat
     */
    public function getChat()
    {
        return new Chat(
            $this->message->chat
        );
    }
}