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

class Events
{
    const EVENT_IN_MESSAGE  = 'inc_message';
    const EVENT_OUT_MESSAGE = 'out_message';
    const EVENT_ANY_MESSAGE = 'any_message';
    const EVENT_INTERVIEW   = 'interview';

    protected $subscribes = [];
    protected $connection;

    public $attached    = false;
    public $terminated  = false;

    public static function join($connection)
    {
        return new static($connection);
    }

    protected function __construct($connection)
    {
        $this->connection = $connection;
        com_event_sink($this->connection , $this, '_ISkypeEvents');
    }

    public function subscribe($type, callable $cb)
    {
        if (!isset($this->subscribes[$type])) {
            $this->subscribes[$type] = [];
        }
        $this->subscribes[$type][] = $cb;
        return count($this->subscribes[$type]);
    }

    public function check($type, $args = [])
    {
        if (!isset($this->subscribes[$type])) {
            return;
        }

        foreach ($this->subscribes[$type] as $callback) {
            call_user_func_array($callback, $args);
        }
    }

    public function close()
    {
        $this->terminated = true;
    }



    public function interview(callable $cb)
    {
        return $this->subscribe(self::EVENT_INTERVIEW, $cb);
    }

    public function onMessage(callable $cb)
    {
        return $this->subscribe(self::EVENT_IN_MESSAGE, $cb);
    }

    public function onSend(callable $cb)
    {
        return $this->subscribe(self::EVENT_OUT_MESSAGE, $cb);
    }

    public function onAnyMessage(callable $cb)
    {
        return $this->subscribe(self::EVENT_ANY_MESSAGE, $cb);
    }


    /**
     * Interface
     */

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