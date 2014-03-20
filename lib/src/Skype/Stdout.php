<?php
/**
 * This file is part of Skype package.
 *
 * serafim <nesk@xakep.ru> (20.03.2014 17:11)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Skype;

class Stdout
{
    public static function write($msg)
    {
        echo $msg . "\n";
        flush();
    }
}