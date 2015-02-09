<?php
namespace com\xcitestudios\Logging\Test;

class LogMessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Check the JSON output adheres to the specified schema.
     */
    public function testSerializationAdherance()
    {
        $datetime    = new \DateTime();
        $severity    = new \com\xcitestudios\Logging\LogSeverity('critical');
        $message     = 'Test Message';
        $messageArgs = [];
        $source      = '127.0.0.1';
        $application = 'PHPUnit';
        $module      = 'Logging';
        $extra       = 'some string';
        
        $firstMessage = new \com\xcitestudios\Logging\LogMessage();
        $firstMessage->setApplication($application);
        $firstMessage->setDateTime($datetime);
        $firstMessage->setExtra($extra);
        $firstMessage->setLogSeverity($severity);
        $firstMessage->setMessage($message);
        $firstMessage->setMessageArgs($messageArgs);
        $firstMessage->setModule($module);
        $firstMessage->setSource($source);
        
        $serialized = $firstMessage->serialize();
        
        $retriever = new \JsonSchema\Uri\UriRetriever;
        $schema = $retriever->retrieve('file://' . realpath('vendor/xcitestudios/json-schemas/com/xcitestudios/schemas/Logging/LogMessage.json'));
        
        $validator = new \JsonSchema\Validator();
        $validator->check(\json_decode($serialized), $schema);
        
        $message = 'Errors: ';
        if (!$validator->isValid()) {
            foreach ($validator->getErrors() as $error) {
                $message .= $error['property'] . ': ' . $error['message'] . "; ";
            }
        }
        
        $this->assertTrue($validator->isValid(), $message);
    }
    
    /**
     * LogMessages can be converted to/from JSON according to the generic interface.
     */
    public function testSerializationConsistency()
    {
        $datetime    = new \DateTime();
        $severity    = new \com\xcitestudios\Logging\LogSeverity('critical');
        $message     = 'Test Message';
        $messageArgs = [];
        $source      = '127.0.0.1';
        $application = 'PHPUnit';
        $module      = 'Logging';
        $extra       = 'some string';
        
        $firstMessage = new \com\xcitestudios\Logging\LogMessage();
        $firstMessage->setApplication($application);
        $firstMessage->setDateTime($datetime);
        $firstMessage->setExtra($extra);
        $firstMessage->setLogSeverity($severity);
        $firstMessage->setMessage($message);
        $firstMessage->setMessageArgs($messageArgs);
        $firstMessage->setModule($module);
        $firstMessage->setSource($source);
        
        $serialized = $firstMessage->serialize();
        
        $secondMessage = new \com\xcitestudios\Logging\LogMessage();
        $secondMessage->deserialize($serialized);
        
        $this->assertEquals($firstMessage->getApplication(), $secondMessage->getApplication());
        $this->assertEquals($firstMessage->getDateTime(), $secondMessage->getDateTime());
        $this->assertEquals($firstMessage->getExtra(), $secondMessage->getExtra());
        $this->assertEquals($firstMessage->getFormattedMessage(), $secondMessage->getFormattedMessage());
        $this->assertEquals($firstMessage->getLogSeverity(), $secondMessage->getLogSeverity());
        $this->assertEquals($firstMessage->getMessage(), $secondMessage->getMessage());
        $this->assertEquals($firstMessage->getMessageArgs(), $secondMessage->getMessageArgs());
        $this->assertEquals($firstMessage->getModule(), $secondMessage->getModule());
        $this->assertEquals($firstMessage->getSource(), $secondMessage->getSource());
        
        $this->assertEquals($firstMessage->getApplication(), $application);
        $this->assertEquals($firstMessage->getDateTime(), $datetime);
        $this->assertEquals($firstMessage->getExtra(), $extra);
        $this->assertEquals($firstMessage->getLogSeverity(), $severity);
        $this->assertEquals($firstMessage->getMessage(), $message);
        $this->assertEquals($firstMessage->getMessageArgs(), $messageArgs);
        $this->assertEquals($firstMessage->getModule(), $module);
        $this->assertEquals($firstMessage->getSource(), $source);
    }
    
    /**
     * The interface defines the use of sprintf - let's make sure it's being used!
     */
    public function testFormattedMessage()
    {
        $message     = 'Test Message %d? %s! %0.4f';
        $messageArgs = [5, 'apple', 2.4];
        
        $formattedMessage = 'Test Message 5? apple! 2.4000';
        
        $logMessage = new \com\xcitestudios\Logging\LogMessage();
        $logMessage->setMessage($message);
        $logMessage->setMessageArgs($messageArgs);
        
        $this->assertEquals($formattedMessage, $logMessage->getFormattedMessage());
    }
}
