<?php
/**
 * This file is part of Skype package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 14:39)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Skype\Client;

/**
 * Class Skype
 */
class Skype
{
    /**
     * Library version
     */
    const VERSION = '1.0';

    /**
     * Input charset
     */
    const CHARSET_SOURCE = 'cp1251';

    /**
     * Output charset
     */
    const CHARSET_TARGET = 'utf-8';

    /**
     * Return skype client
     * @param bool $minimized
     * @param bool $splash
     * @return Client
     */
    public static function client($minimized = true, $splash = false)
    {
        return Client::instance($minimized, $splash);
    }

    /**
     * Close constructor
     */
    private function __construct(){}

    /**
     * Encode string
     * @param $msg
     * @return string
     */
    public static function encode($msg)
    {
        return iconv(self::CHARSET_SOURCE, self::CHARSET_TARGET, $msg);
    }

    /**
     * Decode string
     * @param $msg
     * @return string
     */
    public static function decode($msg)
    {
        if ($result = @iconv(self::CHARSET_TARGET, self::CHARSET_SOURCE, $msg)) {
            return $result;
        }
        return $msg;
    }
}