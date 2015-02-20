<?php
namespace com\xcitestudios\Logging\Interfaces;

use com\xcitestudios\Generic\Data\Manipulation\Interfaces\SerializationInterface;

/**
 * A message to be logged and supports serialization.
 */
interface LogMessageSerializableInterface
    extends LogMessageInterface, SerializationInterface
{
}