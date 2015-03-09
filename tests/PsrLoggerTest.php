<?php
namespace com\xcitestudios\Logging\Test;

use com\xcitestudios\Logging\LogMessage;
use com\xcitestudios\Logging\LogSeverity;
use com\xcitestudios\Logging\PsrLogger;
use InvalidArgumentException;

class PsrLoggerTest extends \PHPUnit_Framework_TestCase
{
    public function testPsrLogger()
    {
        $mockMessage = new LogMessage();
        $configuredMessage = null;

        $testInterface = $this->getMock(\com\xcitestudios\Logging\Interfaces\LoggerInterface::class, ['log']);

        $testInterface->expects($this->once())
            ->method('log')
            ->will($this->returnCallback(function($message) use (&$configuredMessage){
                $configuredMessage = $message;
            }));

        $psrLogger = new PsrLogger($testInterface, $mockMessage);

        $context = [
            'alpha'     => 'THIS_IS_ALPHA',
            'beta'      => 'THIS_IS_BETA',
            'charlie'   => 5,
            'foxtrot'   => 6.81745,
            'exception' => new InvalidArgumentException('Ex Message', 456),
        ];

        $psrLogger->warning('Testing {alpha} of {beta} in {alpha} ignoring {gamma}, {charlie} times for {foxtrot} precision.', $context);

        $this->assertEquals(new LogSeverity(LogSeverity::warning), $configuredMessage->getLogSeverity());
        $this->assertEquals('Testing %s of %s in %s ignoring {gamma}, %d times for %0.8f precision.', $configuredMessage->getMessage());
        $this->assertEquals(['THIS_IS_ALPHA', 'THIS_IS_BETA', 'THIS_IS_ALPHA', 5, 6.81745], $configuredMessage->getMessageArgs());
        $this->assertContains('"Ex Message"', $configuredMessage->getExtra());
        $this->assertContains('456', $configuredMessage->getExtra());
    }
}
