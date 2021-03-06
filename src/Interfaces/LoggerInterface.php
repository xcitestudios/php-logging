<?php
/**
 * com.xcitestudios.Logging
 *
 * @copyright Wade Womersley (xcitestudios)
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://xcitestudios.com/
 */

namespace com\xcitestudios\Logging\Interfaces;

/**
 * Interface for a provider who can handle LogMessage's.
 *
 * @package com.xcitestudios.Logging
 * @subpackage Interfaces
 */
interface LoggerInterface
{
    /**
     * Handle the provided log message however it needs to be handled.
     * 
     * @param LogMessageInterface $message Message to handle
     * @return void
     */
    public function log(LogMessageInterface $message);
}
