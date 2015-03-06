<?php
/**
 * com.xcitestudios.Logging
 *
 * @copyright Wade Womersley (xcitestudios)
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://xcitestudios.com/
 */

namespace com\xcitestudios\Logging;

use SplEnum;
use JsonSerializable;

/**
 * Log severities based on syslog.
 *
 * @package com.xcitestudios.Logging
 */
class LogSeverity extends SplEnum
    implements JsonSerializable
{
    const __default = self::debug;
    
    /**
     * A "panic" condition usually affecting multiple apps/servers/sites. At this level it would usually 
     * notify all tech staff on call.
     */
    const emergency = 0;

    /**
     * Should be corrected immediately, therefore notify staff who can fix the problem. An example would be 
     * the loss of a primary ISP connection.
     */
    const alert = 1;

    /**
     * Should be corrected immediately, but indicates failure in a secondary system, an example is a loss 
     * of a backup ISP connection.
     */
    const critical = 2;

    /**
     * Non-urgent failures, these should be relayed to developers or admins; each item must be resolved 
     * within a given time.
     */
    const error = 3;

    /**
     * Warning messages, not an error, but indication that an error will occur if action is not taken, 
     * e.g. file system 85% full - each item must be resolved within a given time.
     */
    const warning = 4;

    /**
     * Events that are unusual but not error conditions - might be summarized in an email to developers 
     * or admins to spot potential problems - no immediate action required.
     */
    const notice = 5;

    /**
     * Normal operational messages - may be harvested for reporting, measuring throughput, etc. - no action required.
     */
    const informational = 6;

    /**
     * Info useful to developers for debugging the application, not useful during operations.
     */
    const debug = 7;
    
    /**
     * Allow setting value by syslog string (constant name).
     * 
     * @param string|int $value const or string, one of: emergency, alert, critical, error, warning, notice, informational, debug
     * @throws RuntimeException if the string value is invalid
     */
    public function __construct($value = self::__default)
    {
        if (gettype($value) === 'string') {
            if (!array_key_exists($value, $this->getConstList())) {
                throw new \RuntimeException(sprintf('String value for %s is not valid, got %s', __CLASS__, $value));
            }
            
            $value = $this->getConstList()[$value];
        }
        
        parent::__construct($value);
    }

    /**
     * Create a log severity from a string.
     *
     * @param string $severity
     *
     * @return LogSeverity
     */
    public static function fromString($severity)
    {
        $ret = new LogSeverity();
        $value = $ret->getConstList()[$severity];
        return new LogSeverity($value);
    }
    
    /**
     * Return the syslog string value when cast to string.
     * 
     * @return string
     */
    public function __toString()
    {
        return array_flip($this->getConstList())[(int)$this];
    }

    /**
     * We should always be a string.
     * 
     * @return string
     */
    public function jsonSerialize() {
        return (string)$this;
    }
}