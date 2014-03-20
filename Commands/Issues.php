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

class Issues
{
    public $about   = 'Открытые вопросы по форуму на Github';
    public $command = 'github:issues';

    private $github;

    public function __construct()
    {
        $this->github = new Github();
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
}