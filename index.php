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
use Skype\Stdout;

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
        $this->commands[] = new Commands\GitHubIssues();
        $this->commands[] = new Commands\RedmineIssues();

        Stdout::write('Initialize skype devel client');

        $this->skype  = Skype::client();
        $this->chat   = Chat::getByName(self::CHAT_ID);
        #$this->chat   = Chat::getByName(self::CHAT_ECHO);

        (new Timer(120))
            ->callback(function(){
                foreach ($this->commands as $c) {
                    $c->check();
                }
            })
            ->join($this->skype);


        $this->skype->events->onAnyMessage(function(Message $msg){

            /**
             * HELP
             */
            if ($msg->like('help')) {
                Stdout::write(
                    'User ' . $msg->getUser()->handle . ' made a new help request'
                );

                $result = 'Доступные команды:' . "\n";
                foreach ($this->commands as $c) {
                    $result .= '   ' . $c->command . ' - ' . $c->about . "\n";
                }
                $msg->getUser()->send($result);


            /**
             * COMMANDS
             */
            } else {
                foreach ($this->commands as $c) {
                    if ($msg->like($c->command)) {
                        Stdout::write(
                            'User ' . $msg->getUser()->handle . ' made a new ' . $c->command . ' request'
                        );
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
    /**
     * EXCEPTION
     */
    Stdout::write(
        'Exception: ' . $e->getMessage() . "\n" .
        'Trace: ' . "\n" . print_r($e->getTrace(), 1)
    );
}



