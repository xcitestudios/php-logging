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
use Psr\Log\AbstractLogger;
use DateTime;
use Exception;
use stdClass;

/**
 * If you want to use a PSR3 logger, this uses the interface but internally
 * uses  com\xcitestudios\Logging\Interfaces\LoggerInterface.
 *
 * As PSR defines context to be an associative array, some magic will be done
 * to convert this to a sprintf format for message and messageArgs.
 *
 * If exception exists in context it will be moved as JSON into the extra field.
 *
 * @package com.xcitestudios.Logging
 */
class PsrLogger extends AbstractLogger
{
    /**
     * @var LoggerInterface
     */
    protected $xcitestudiosLogger;

    /**
     * @var LogMessage
     */
    protected $logMessage;

    /**
     * Pass in the LoggerInterface interface to use underneath.
     * Also pass in a LogMessage pre-built with all but the level, datetime, message and messageArgs.
     *
     * @param LoggerInterface     $logger
     * @param LogMessageInterface $baseLogMessage
     */
    public function __construct(LoggerInterface $logger, LogMessageInterface $baseLogMessage)
    {
        $this->xcitestudiosLogger = $logger;
        $this->logMessage         = $baseLogMessage;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        if ($level === 'info') {
            $level = 'informational';
        }

        $severity   = new LogSeverity($level);
        $logMessage = clone $this->logMessage; /** @var LogMessage $logMessage */

        $logMessage->setLogSeverity($severity)
            ->setDateTime(new DateTime());

        $logMessage = $this->fixMessageAndGetArgs($logMessage, $message, $context);

        $this->xcitestudiosLogger->log($logMessage);
    }

    /**
     * Converts a message using {KEY} and key=>value context to a sprintf format.
     * Far from fool-proof but it should work for most use cases of the PSR logger.
     *
     * @param LogMessageInterface $logMessage
     * @param string              $message
     * @param array               $context
     *
     * @return LogMessage
     */
    protected function fixMessageAndGetArgs(LogMessageInterface $logMessage, $message, $context)
    {
        if (count($context) === 0) {
            $logMessage->setMessage($message);
            $logMessage->setMessageArgs([]);
            return $logMessage;
        }

        // Let's try and deal with Exceptions by using the extra field.
        if (array_key_exists('exception', $context) && $context['exception'] instanceof Exception) {

            $exception = $context['exception']; /** @var Exception $exception */

            $extra                     = new stdClass();
            $extra->exception          = new stdClass();
            $extra->exception->code    = $exception->getCode();
            $extra->exception->file    = $exception->getFile();
            $extra->exception->line    = $exception->getLine();
            $extra->exception->message = $exception->getMessage();
            $extra->exception->trace   = $exception->getTraceAsString();

            $originalExtra = $logMessage->getExtra() ? $logMessage->getExtra() . ';exception=' : '';
            $logMessage->setExtra($originalExtra . json_encode($extra));

        }

        preg_match_all('#\{[A-Za-z0-9-]+\}#is', $message, $matches, \PREG_OFFSET_CAPTURE);

        $matches = array_reverse($matches[0]);
        $args    = [];

        foreach ($matches as $match) {
            $matchedString = $match[0];
            $offset        = $match[1];
            $key           = substr($matchedString, 1, -1);

            if (array_key_exists($key, $context)) {
                if (is_int($context[$key])) {
                    $type  = '%d';
                    $value = (int)$context[$key];
                } else if (is_float($context[$key])) {
                    $type  = '%0.8f';   //quite specific float - but we don't know how specific the user wants
                    $value = (float)$context[$key];
                } else if (is_array($context[$key]) || is_object($key)) {
                    $type = '%s';
                    $value = (string)json_encode($context[$key]);
                } else {
                    $type  = '%s';
                    $value = (string)$context[$key];
                }

                $message = substr($message, 0, $offset) . $type . substr($message, $offset + strlen($matchedString));
                $args[]  = $value;
            }
        }

        $args = array_reverse($args);

        $logMessage->setMessage($message);
        $logMessage->setMessageArgs($args);

        return $logMessage;
    }
}