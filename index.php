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
use Github\Client as Github;

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
     * Github instance
     * @var
     */
    protected $github;

    public function __construct()
    {
        $this->skype  = Skype::client();
        #$this->chat   = Chat::getByName(self::CHAT_ID);
        #$this->chat   = Chat::getByName(self::CHAT_ECHO);
        #$this->github = new Github();

        (new Timer(10))
            ->callback(function(){ /* $this->checkGithub(); */ })
            ->join($this->skype);

        $this->skype->events->onMessage(function(Message $msg){
            $msg->getUser()->send('Сам такой!');
            #if ($msg->like('github:issues')) {
            #    $m = '';
            #    foreach ($this->issues() as $issue) {
            #        $m .= 'New issue: "' . $issue['title'] . '" ' . $issue['url'] . "\n\n";
            #    }
            #    $msg->getUser()->send($m);
            #}
        });

        $this->skype->join();
    }

    public function issues()
    {
        return $this->github
            ->api('issue')
            ->all('codersclub', 'forum');
    }
}
new DevelClient();



