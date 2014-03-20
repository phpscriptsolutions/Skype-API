<?php
/**
 * This file is part of Skype package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 16:27)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skype;

use Skype\TSkype\Singleton;
use Skype\TSkype\Proxy;
use Skype\Events;
use COM;

/**
 * Class Client
 * @package Skype
 */
class Client
{
    use Singleton;
    use Proxy;

    /**
     * Skype COM instance
     * @var
     */
    protected $connection;

    /**
     * COM variable name
     * @var string
     */
    protected $proxy = 'connection';

    /**
     * Skype events instance
     * @var
     */
    public $events;

    /**
     * Singleton constructor
     * @param bool $minimized
     * @param bool $splash
     */
    public function initialize($minimized = true, $splash = false)
    {
        $this->connection   = new COM('Skype4COM.Skype');
        $this->events       = Events::join($this->connection);

        if (!$this->client()->isRunning()) {
            $this->client()->start($minimized, $splash);
        }

        $this->attach();
        com_message_pump(1000);
    }

    /**
     * Default skype attach method
     */
    public function attach()
    {
        $this->connection->attach(5, false);
    }

    /**
     * Run skype client
     */
    public function join()
    {
        while (!$this->events->terminated) {
            com_message_pump(10);
            $this->checkAttach();
            $this->events->check(Events::EVENT_INTERVIEW, [time(), microtime(true)]);
        }
    }

    /**
     * Attach status timeout
     * @var
     */
    private $_awaitMinute;

    /**
     * Check skype attach status
     */
    private function checkAttach()
    {
        if (date('H:i') != $this->_awaitMinute) {
            if ($this->attachmentStatus->value != 0 ) {
                $this->attach(5,false);
            }
            $this->_awaitMinute = date('H:i');
        }
    }
}