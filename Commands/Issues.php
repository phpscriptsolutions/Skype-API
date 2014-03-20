<?php
/**
 * This file is part of Skype package.
 *
 * serafim <nesk@xakep.ru> (20.03.2014 13:42)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Commands;

use Skype\User;
use Github\Client as Github;
use Skype;
use Skype\Chat;
use Commands\AbstractCommand;

class Issues extends AbstractCommand
{
    public $about   = 'Открытые вопросы по форуму на Github';
    public $command = 'github:issues';

    private $github;
    private $issues;

    public function __construct()
    {
        $this->github = new Github();
        $this->updateIssues();
    }

    protected function updateIssues()
    {
        $iss = $this->github
            ->api('issue')
            ->all('codersclub', 'forum');

        foreach ($iss as $i) {
            $this->issues[$i['id']] = $i;
        }
    }


    public function request(User $user)
    {
        $issues = $this->github
            ->api('issue')
            ->all('codersclub', 'forum');

        $result = $this->about . ":\n";
        foreach ($issues as $i) {
            $result .= '   ' . $i['title'] . ' : ' . $i['url'] . "\n";
        }

        if (Skype::client()->currentUserHandle != $user->handle) {
            $user->send($result);
        }
    }

    public function check()
    {
        $issues = $this->github
            ->api('issue')
            ->all('codersclub', 'forum');

        foreach ($issues as $iss) {
            if (!isset($this->issues[$iss['id']])) {
                Chat::getByName(\DevelClient::CHAT_ID)
                    ->send(
                        'Открыт новый вопрос по форуму: ' . "\n" .
                        '  Вопрос: ' . $iss['title'] .      "\n" .
                        '  Описание: ' . $iss['body'] .     "\n" .
                        '  Адрес: ' . $iss['html_url'] .    "\n" .
                        '  Автор: ' . $iss['user']['login']
                    );
            }
        }
        $this->updateIssues();
    }
}