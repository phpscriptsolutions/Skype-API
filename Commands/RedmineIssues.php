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

class RedmineIssues extends AbstractCommand
{
    public $about   = 'Открытые задачи по форуму в Redmine';
    public $command = 'redmine:issues';

    private $issues;

    public function __construct()
    {

    }

    public function request(User $user)
    {

    }

    public function check()
    {

    }
}