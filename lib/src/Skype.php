<?php
/**
 * This file is part of Skype package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 14:39)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

#use \COM;
use Skype\Client;

/**
 * Class Skype
 */
class Skype
{
    const VERSION = '1.0';

    const CHARSET_SOURCE = 'cp1251';
    const CHARSET_TARGET = 'utf-8';

    public static function client($minimized = true, $splash = false)
    {
        return Client::instance($minimized, $splash);
    }

    private function __construct() {}

    public static function encode($msg)
    {
        return iconv(self::CHARSET_SOURCE, self::CHARSET_TARGET, $msg);
    }

    public static function decode($msg)
    {
        if ($result = @iconv(self::CHARSET_TARGET, self::CHARSET_SOURCE, $msg)) {
            return $result;
        }
        return $msg;
    }
}