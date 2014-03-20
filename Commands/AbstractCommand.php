<?php
/**
 * This file is part of Skype package.
 *
 * serafim <nesk@xakep.ru> (20.03.2014 16:47)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Commands;

use Skype\User;

abstract class AbstractCommand
{
    public $about   = 'undefined';
    public $command = 'undefined';


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