<?php
/**
 * This file is part of Skype package.
 *
 * serafim <nesk@xakep.ru> (19.03.2014 15:15)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skype;

/**
 * Class Environment
 * Skype4com configurator
 * @package Skype
 */
class Environment
{
    /**
     * Drive for search needle .dll file
     * @var null|string
     */
    private $_drive = 'C';

    /**
     * @param null $drive
     */
    public function __construct($drive = null)
    {
        $this->_drive = $drive ? $drive : $this->_drive;
    }

    /**
     * Search .dll
     * @return string
     * @throws \Exception
     */
    public function search()
    {
        $path86 = $this->_drive . ':\\Program Files (x86)\\Common Files\\Skype\\Skype4COM.dll';
        $path64 = $this->_drive . ':\\Program Files\\Common Files\\Skype\\Skype4COM.dll';

        if (file_exists($path86)) {
            return $path86;
        } else if (file_exists($path64)) {
            return $path64;
        }

        throw new \Exception('Can not find Skype4COM.dll');
    }

    /**
     * Register Skype ActiveX
     */
    public function register()
    {
        $path = $this->search();
        exec('regsvr32 "' . $path . '"');
    }
}