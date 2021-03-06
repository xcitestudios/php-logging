<?php
/**
 * com.xcitestudios.Logging
 *
 * @copyright Wade Womersley (xcitestudios)
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://xcitestudios.com/
 */

namespace com\xcitestudios\Logging\Interfaces;

use com\xcitestudios\Logging\LogSeverity;
use com\xcitestudios\Generic\Data\Manipulation\Interfaces\SerializationInterface;
use DateTime;

/**
 * A message to be logged.
 *
 * @package com.xcitestudios.Logging
 * @subpackage Interfaces
 */
interface LogMessageInterface
{
    /**
     * Set the severity of this log message.
     *
     * @param LogSeverity $severity ENUM log severity
     * @return void
     */
    public function setLogSeverity(LogSeverity $severity);
    
    /**
     * Get the severity of this log message.
     *
     * @return LogSeverity
     */
    public function getLogSeverity();

    /**
     * Set the datetime this log event occured (ISO8601 combined date/time format (including timezone) for storage).
     *
     * @param DateTime $datetime Date/Time log occurred.
     * @return void
     */
    public function setDateTime(DateTime $datetime);
    
    /**
     * Get the datetime this log event occured (ISO8601 combined date/time format (including timezone) for storage).
     *
     * @return DateTime
     */
    public function getDateTime();
    
    /**
     * Set the identifier of the machine the log came from (IP, DNS name etc, any unique identifier).
     *
     * @param string $source Source of the message.
     * @return void
     */
    public function setSource($source);
    
    /**
     * Get the identifier of the machine the log came from (IP, DNS name etc, any unique identifier).
     *
     * @return string
     */
    public function getSource();
    
    /**
     * Set the application that raised the log.
     *
     * @param string $application application this log relates to.
     * @return void
     */
    public function setApplication($application);
    
    /**
     * Get the application that raised the log.
     *
     * @return string
     */
    public function getApplication();

    /**
     * Set the optional module in the application that raised the log.
     *
     * @param string $module module this log relates to in the application.
     * @return void
     */
    public function setModule($module);
    
    /**
     * Get the optional module in the application that raised the log.
     *
     * @return string
     */
    public function getModule();

    /**
     * Set the message for the log but use printf standard for arguments where requirement.
     *
     * @param string $message message to return for the log
     * @return void
     */
    public function setMessage($message);
    
    /**
     * Get the message for the log but use printf standard for arguments where requirement.
     *
     * @return string
     */
    public function getMessage();
    
    /**
     * Sets the arguments to format into message.
     *
     * @param array $args arguments
     * @return void
     */
    public function setMessageArgs(array $args);
    
    /**
     * Gets the arguments to format into message.
     *
     * @return array arguments
     */
    public function getMessageArgs();
    
    /**
     * Gets message using messageArgs and printf.
     *
     * @return string
     */
    public function getFormattedMessage();

    /**
     * Set any extra data to store alongside the log entry.    
     *
     * @param string $extra
     * @return void
     */
    public function setExtra($extra);
    
    /**
     * Get any extra data to store alongside the log entry.    
     *
     * @return string
     */
    public function getExtra();
}