<?php
/**
 * com.xcitestudios.Logging
 *
 * @copyright Wade Womersley (xcitestudios)
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://xcitestudios.com/
 */

namespace com\xcitestudios\Logging;

use com\xcitestudios\Logging\Interfaces\LoggerInterface;
use com\xcitestudios\Logging\Interfaces\LogMessageInterface;
use com\xcitestudios\Logging\Interfaces\LogMessageSerializableInterface;
use stdClass;
use DateTime;
use JsonSerializable;

/**
 * Help with logging to AMQP - easier key and JSON content generation.
 *
 * @package com.xcitestudios.Logging
 */
abstract class AMQPExchangeLogger implements LoggerInterface
{
    /**
     * Generate a string used for the key in the exchange publish.
     *
     * @param LogMessageInterface $message
     *
     * @return string
     */
    protected function generateKey(LogMessageInterface $message)
    {
        $keyParts = [
            $message->getSource(),
            $message->getApplication(),
        ];

        if ($message->getModule() !== null) {
            $keyParts[] = $message->getModule();
        }

        $keyParts[] = $message->getLogSeverity();

        return implode('.', $keyParts);
    }

    /**
     * If it's a LogMessageSerializableInterface instance, call serializeJSON (ideal).
     * If it implements JsonSerialize, call that.
     * Otherwise pull out what we know about LogMessageInterface and return that.
     *
     * @param LogMessageInterface $message
     *
     * @return string
     */
    protected function getMessageJSON(LogMessageInterface $message)
    {
        if ($message instanceof LogMessageSerializableInterface) {
            return $message->serializeJSON();
        } elseif ($message instanceof JsonSerializable) {
            return json_encode($message);
        }

        $ret = new stdClass();

        $ret->application = $message->getApplication();
        $ret->datetime = $message->getDateTime()->format(DateTime::ISO8601);
        $ret->extra = $message->getExtra();
        $ret->message = $message->getMessage();
        $ret->messageArgs = $message->getMessageArgs();
        $ret->severity = $message->getLogSeverity();
        $ret->module = $message->getModule();
        $ret->source = $message->getSource();

        return json_encode($ret);
    }
}