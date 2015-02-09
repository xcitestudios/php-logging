<?php
namespace com\xcitestudios\Logging\Test;

class LogSeverityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The log types are 100% standard from syslog - they should never change.
     */
    public function testEnumIntegerValues()
    {
        $this->assertEquals(0, \com\xcitestudios\Logging\LogSeverity::emergency);
        $this->assertEquals(1, \com\xcitestudios\Logging\LogSeverity::alert);
        $this->assertEquals(2, \com\xcitestudios\Logging\LogSeverity::critical);
        $this->assertEquals(3, \com\xcitestudios\Logging\LogSeverity::error);
        $this->assertEquals(4, \com\xcitestudios\Logging\LogSeverity::warning);
        $this->assertEquals(5, \com\xcitestudios\Logging\LogSeverity::notice);
        $this->assertEquals(6, \com\xcitestudios\Logging\LogSeverity::informational);
        $this->assertEquals(7, \com\xcitestudios\Logging\LogSeverity::debug);
    }
    
    /**
     * The log types are 100% standard from syslog - they should never change.
     */
    public function testEnumStringValues()
    {
        $this->assertEquals('emergency', (string)(new \com\xcitestudios\Logging\LogSeverity(\com\xcitestudios\Logging\LogSeverity::emergency)));
        $this->assertEquals('alert', (string)(new \com\xcitestudios\Logging\LogSeverity(\com\xcitestudios\Logging\LogSeverity::alert)));
        $this->assertEquals('critical', (string)(new \com\xcitestudios\Logging\LogSeverity(\com\xcitestudios\Logging\LogSeverity::critical)));
        $this->assertEquals('error', (string)(new \com\xcitestudios\Logging\LogSeverity(\com\xcitestudios\Logging\LogSeverity::error)));
        $this->assertEquals('warning', (string)(new \com\xcitestudios\Logging\LogSeverity(\com\xcitestudios\Logging\LogSeverity::warning)));
        $this->assertEquals('notice', (string)(new \com\xcitestudios\Logging\LogSeverity(\com\xcitestudios\Logging\LogSeverity::notice)));
        $this->assertEquals('informational', (string)(new \com\xcitestudios\Logging\LogSeverity(\com\xcitestudios\Logging\LogSeverity::informational)));
        $this->assertEquals('debug', (string)(new \com\xcitestudios\Logging\LogSeverity(\com\xcitestudios\Logging\LogSeverity::debug)));   
        
        $this->setExpectedException(\RuntimeException::class, 'is not valid');
        new \com\xcitestudios\Logging\LogSeverity('badthing');
    }
    
    public function testEnumFromString()
    {
        $this->assertEquals('emergency', (string)(new \com\xcitestudios\Logging\LogSeverity('emergency')));
        $this->assertEquals('alert', (string)(new \com\xcitestudios\Logging\LogSeverity('alert')));
        $this->assertEquals('critical', (string)(new \com\xcitestudios\Logging\LogSeverity('critical')));
        $this->assertEquals('error', (string)(new \com\xcitestudios\Logging\LogSeverity('error')));
        $this->assertEquals('warning', (string)(new \com\xcitestudios\Logging\LogSeverity('warning')));
        $this->assertEquals('notice', (string)(new \com\xcitestudios\Logging\LogSeverity('notice')));
        $this->assertEquals('informational', (string)(new \com\xcitestudios\Logging\LogSeverity('informational')));
        $this->assertEquals('debug', (string)(new \com\xcitestudios\Logging\LogSeverity('debug')));
    }
    
    /**
     * Default is debug.
     */
    public function testEnumDefault()
    {
        $default = new \com\xcitestudios\Logging\LogSeverity();
        $severity = new \com\xcitestudios\Logging\LogSeverity(\com\xcitestudios\Logging\LogSeverity::debug);
        
        $this->assertEquals($default, $severity);
    }
}
