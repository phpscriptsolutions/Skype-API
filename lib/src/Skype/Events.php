<?php
/**
 * This file is part of Corruption package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 20:02)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Skype;

use Skype\Helper\ProxyInstance;
use Skype\Message;
use Skype\User;
use Skype\Exception\SkypeException;

/**
 * Class Events
 * Skype2com event handler
 * @package Skype
 */
class Events
{
    const EVENT_IN_MESSAGE  = 'inc_message';
    const EVENT_OUT_MESSAGE = 'out_message';
    const EVENT_ANY_MESSAGE = 'any_message';
    const EVENT_INTERVIEW   = 'interview';
    const EVENT_AUTHORIZE   = 'auth';

    /**
     * All subscribes
     * @var array
     */
    protected $subscribes = [];

    /**
     * Skype COM instance
     * @var
     */
    protected $connection;

    /**
     * Attached status
     * @var bool
     */
    public $attached    = false;

    /**
     * Running status
     * @var bool
     */
    public $terminated  = false;

    /**
     * Join events to Skype COM object
     * @param $connection
     * @return static
     */
    public static function join($connection)
    {
        return new static($connection);
    }

    /**
     * @param $connection
     */
    protected function __construct($connection)
    {
        $this->connection = $connection;
        com_event_sink($this->connection , $this, '_ISkypeEvents');
    }

    /**
     * Add subscribe
     * @param $type
     * @param callable $cb
     * @return int
     */
    public function subscribe($type, callable $cb)
    {
        if (!isset($this->subscribes[$type])) {
            $this->subscribes[$type] = [];
        }
        $this->subscribes[$type][] = $cb;
        return count($this->subscribes[$type]);
    }

    /**
     * Call all subscribes
     * @param $type
     * @param array $args
     */
    public function check($type, $args = [])
    {
        if (!isset($this->subscribes[$type])) {
            return;
        }

        foreach ($this->subscribes[$type] as $callback) {
            call_user_func_array($callback, $args);
        }
    }

    /**
     * Stop client
     */
    public function close()
    {
        $this->terminated = true;
    }


    /**
     * Call each iteration event
     * @param callable $cb
     * @return int
     */
    public function interview(callable $cb)
    {
        return $this->subscribe(self::EVENT_INTERVIEW, $cb);
    }

    /**
     * Income message event
     * @param callable $cb
     * @return int
     */
    public function onMessage(callable $cb)
    {
        return $this->subscribe(self::EVENT_IN_MESSAGE, $cb);
    }

    /**
     * Send message event
     * @param callable $cb
     * @return int
     */
    public function onSend(callable $cb)
    {
        return $this->subscribe(self::EVENT_OUT_MESSAGE, $cb);
    }

    /**
     * Any new message event
     * @param callable $cb
     * @return int
     */
    public function onAnyMessage(callable $cb)
    {
        return $this->subscribe(self::EVENT_ANY_MESSAGE, $cb);
    }

    /**
     * Any new authorization request
     * @param callable $cb
     * @return int
     */
    public function onAuthorize(callable $cb)
    {
        return $this->subscribe(self::EVENT_AUTHORIZE, $cb);
    }


    /**********************
     * Events Interface
     **********************/

    public function Error($command, $num, $desc)
    {
        throw new SkypeException($num . ': Skype bad command ' . $command->command .
            ' existing. ' . $desc);
    }

    /**
     * @param $user
     */
    public function UserAuthorizationRequestReceived($user)
    {
        $this->check(self::EVENT_AUTHORIZE, [new User($user)]);
    }

    /**
     * @param $status
     */
    public function AttachmentStatus($status)
    {
        if ($status = $this->connection->Convert->TextToAttachmentStatus("AVAILABLE")) {
            \Skype::client()->attach();
        }
        $this->attached = true;
    }

    /**
     * On message event
     * $status:
     *   0 - написано
     *   1 - дошло
     *   2 - входящее
     *   3 - прочитано
     * @param $message
     * @param $status
     */
    public function MessageStatus($message, $status)
    {
        if ($status == 2) {
            $this->check(self::EVENT_IN_MESSAGE, [new Message($message)]);
        } else if ($status == 1) {
            $this->check(self::EVENT_OUT_MESSAGE, [new Message($message)]);
        }

        if ($status == 1 || $status == 2) {
            $this->check(self::EVENT_ANY_MESSAGE, [new Message($message)]);
        }
    }
}