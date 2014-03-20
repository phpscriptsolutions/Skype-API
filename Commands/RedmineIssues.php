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

use Skype\Chat;
use Skype\Stdout;
use Skype\User;
use Skype;
use Redmine\Client;

class RedmineIssues extends AbstractCommand
{
    public $about   = 'Открытые задачи по форуму в Redmine';
    public $command = 'redmine:issues';

    private $issues = [];
    private $redmine;

    public function __construct()
    {
        $this->redmine = new Client('http://redmine.rudev.org/', '2208c945f04cbb3145df28195ae038668d2ab897');
        $this->updateIssues();
    }

    private function updateIssues()
    {
        $issues = $this
            ->redmine
            ->api('issue')
            ->all(['limit' => 10])['issues'];

        foreach ($issues as $iss) {
            $this->issues[$iss['id']] = $iss;
        }
    }

    public function request(User $user)
    {
        $result = $this->about . ' (последние 10)'. "\n";
        foreach ($this->issues as $iss) {
            $result .=
                'Заголовок: ' . $iss['project']['name'] . ': ' . $iss['subject'] . "\n" .
                'Описание: '  . $iss['description'] . "\n" .
                'Адрес: '     . 'http://redmine.rudev.org/issues/' . $iss['id'] . "\n\n";
        }
        $user->send($result);
    }

    public function check()
    {
        $issues = $this
            ->redmine
            ->api('issue')
            ->all(['limit' => 10])['issues'];

        foreach ($issues as $issue) {
            if (!isset($this->issues[$issue['id']])) {
                $msg = 'Новая задача в Redmine: ' . "\n" .
                    'Заголовок: ' . $issue['project']['name'] . ': ' . $issue['subject'] . "\n" .
                    'Описание: '  . $issue['description'] . "\n" .
                    'Адрес: '     . 'http://redmine.rudev.org/issues/' . $issue['id'] . "\n\n";

                Chat::getByName(\DevelClient::CHAT_ID)
                    ->send($msg);
            }
        }

        $this->updateIssues();
    }
}