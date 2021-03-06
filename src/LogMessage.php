<?php
/**
 * com.xcitestudios.Logging
 *
 * @copyright Wade Womersley (xcitestudios)
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://xcitestudios.com/
 */

namespace com\xcitestudios\Logging;

use DateTime;
use JsonSerializable;
use stdClass;

/**
 * A basic implementation of a the LoggMessageSerializableInterface.
 *
 * @package com.xcitestudios.Logging
 */
class LogMessage implements Interfaces\LogMessageSerializableInterface,
    JsonSerializable
{
    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $application;

    /**
     * @var string|null
     */
    protected $module = null;

    /**
     * @var DateTime
     */
    protected $datetime;

    /**
     * @var string
     */
    protected $extra;

    /**
     * @var LogSeverity
     */
    protected $severity;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $messageArgs = [];

    /**
     * Set the severity of this log message.
     *
     * @param LogSeverity $severity ENUM log severity
     * @return static
     */
    public function setLogSeverity(LogSeverity $severity)
    {
        $this->severity = $severity;

        return $this;
    }

    /**
     * Get the severity of this log message.
     *
     * @return LogSeverity
     */
    public function getLogSeverity()
    {
        return $this->severity;
    }

    /**
     * Set the datetime this log event occured (ISO8601 combined date/time format (including timezone) for storage).
     *
     * @param DateTime $datetime Date/Time log occurred.
     * @return static
     */
    public function setDateTime(DateTime $datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get the datetime this log event occured (ISO8601 combined date/time format (including timezone) for storage).
     *
     * @return DateTime
     */
    public function getDateTime()
    {
        return $this->datetime;
    }

    /**
     * Set the identifier of the machine the log came from (IP, DNS name etc, any unique identifier).
     *
     * @param string $source Source of the message.
     * @return static
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get the identifier of the machine the log came from (IP, DNS name etc, any unique identifier).
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set the application that raised the log.
     *
     * @param string $application application this log relates to.
     * @return static
     */
    public function setApplication($application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get the application that raised the log.
     *
     * @return string
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set the optional module in the application that raised the log.
     *
     * @param string $module module this log relates to in the application.
     * @return static
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get the optional module in the application that raised the log.
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set the message for the log but use printf standard for arguments where requirement.
     *
     * @param string $message message to return for the log
     * @return static
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the message for the log but use printf standard for arguments where requirement.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the arguments to format into message.
     *
     * @param array $args arguments
     * @return static
     */
    public function setMessageArgs(array $args)
    {
        $this->messageArgs = $args;

        return $this;
    }

    /**
     * Gets the arguments to format into message.
     *
     * @return array arguments
     */
    public function getMessageArgs()
    {
        return $this->messageArgs;
    }

    /**
     * Gets message using messageArgs and printf.
     *
     * @return string
     */
    public function getFormattedMessage()
    {
        $funcArgs = array_merge([$this->message], $this->messageArgs);

        return call_user_func_array('\sprintf', $funcArgs);
    }

    /**
     * Set any extra data to store alongside the log entry.    
     *
     * @param string $extra
     * @return static
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get any extra data to store alongside the log entry.    
     *
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * Updates the element implementing this interface using a JSON representation. 
     * This means updating the state of this object with that defined in the JSON 
     * as opposed to returning a new instance of this object.
     *
     * @param string $jsonString Representation of the object
     * @return void
     */
    public function deserializeJSON($jsonString)
    {
        $data = \json_decode($jsonString);
        
        $this->setSource($data->source);
        $this->setApplication($data->application);
        $this->setModule($data->module);
        $this->setDateTime(new DateTime($data->datetime));
        
        if (property_exists($data, 'extra')) {
            $this->setExtra($data->extra);
        } else {
            $this->setExtra(null);
        }
        
        $this->setLogSeverity(new LogSeverity($data->severity));
        $this->setMessage($data->message);
        
        if(property_exists($data, 'messageArgs')) {
            $this->setMessageArgs($data->messageArgs);
        } else {
            $this->setMessageArgs([]);
        }
    }

    /**
     * Convert this object into JSON so it can be handled by anything that supports JSON.
     *
     * @return string A JSON representation of this object.
     */
    public function serializeJSON()
    {
        return \json_encode($this);
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        $ret = new stdClass();

        $ret->source      = $this->getSource();
        $ret->application = $this->getApplication();
        $ret->module      = $this->getModule();
        $ret->datetime    = $this->getDateTime()->format('c');
        $ret->extra       = $this->getExtra();
        $ret->severity    = (string)$this->getLogSeverity();
        $ret->message     = $this->getMessage();
        $ret->messageArgs = $this->getMessageArgs();

        return $ret;
    }
}
