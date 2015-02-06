<?php
namespace com\xcitestudios\Logging\Interfaces;

/**
 * Interface for a provider who can handle LogMessage's.
 */
interface LoggerInterface
{
    /**
     * Handle the provided log message however it needs to be handled.
     * 
     * @param LogMessageInterface Message to handle
     * @return void
     */
    public function log(LogMessageInterface $message);
}
