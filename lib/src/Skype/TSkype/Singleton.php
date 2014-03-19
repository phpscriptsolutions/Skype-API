<?php
/**
 * This file is part of Corruption package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 20:24)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skype\TSkype;

/**
 * Class Singleton
 * @package Skype\TSkype
 */
trait Singleton
{
    /**
     * @var array
     */
    private static $_instances = [];

    final private function __construct(){}
    final private function __clone(){}
    final private function __wakeup(){}

    /**
     * @return static
     */
    public static function instance()
    {
        $cls        = get_called_class();

        if (!isset(self::$_instances[$cls])) {
            self::$_instances[$cls] = new static;

            if (method_exists(self::$_instances[$cls], 'initialize')) {
                self::$_instances[$cls]->initialize(func_get_args());
            }
        }

        return self::$_instances[$cls];
    }


}