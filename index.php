<?php
/**
 * This file is part of Skype package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 14:40)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__ . '/vendor/autoload.php';

#use Skype;
use Skype\Chat;
use Skype\Helper\Timer;
use Skype\Message;

/**
 * Class DevelClient
 */
class DevelClient
{
    /**
     * Devel Skype
     */
    const CHAT_ID   = '#nkiryshka/$boris.vorontsov;ac6777775ee6f31e';

    /**
     * Echo service
     */
    const CHAT_ECHO = '#nkiryshka/$echo123;c3a5770b497e7d02';

    /**
     * Skype connection instance
     * @var static
     */
    protected $skype;

    /**
     * Chat instance
     * @var
     */
    protected $chat;

    /**
     * Commands
     */
    protected $commands = [];



    public function __construct()
    {
        $this->commands[] = new Commands\Issues();


        $this->skype  = Skype::client();
        $this->chat   = Chat::getByName(self::CHAT_ID);
        #$this->chat   = Chat::getByName(self::CHAT_ECHO);

        (new Timer(100))
            ->callback(function(){
                foreach ($this->commands as $c) {
                    $c->check();
                }
            })
            ->join($this->skype);


        $this->skype->events->onAnyMessage(function(Message $msg){

            if ($msg->like('help')) {
                $result = 'Доступные команды:' . "\n";
                foreach ($this->commands as $c) {
                    $result .= '   ' . $c->command . ' - ' . $c->about . "\n";
                }
                $msg->getUser()->send($result);
            } else {
                foreach ($this->commands as $c) {
                    if ($msg->like($c->command)) {
                        $c->request($msg->getUser());
                    }
                }
            }
        });


        $this->skype->join();
    }
}

try {
    new DevelClient();
} catch(\Exception $e) {
    Chat::getByName(DevelClient::CHAT_ID)
        ->send('Devel bot exception: ' . $e->getMessage());
}



