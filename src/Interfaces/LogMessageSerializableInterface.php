<?php
/**
 * com.xcitestudios.Logging
 *
 * @copyright Wade Womersley (xcitestudios)
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://xcitestudios.com/
 */

namespace com\xcitestudios\Logging\Interfaces;

use com\xcitestudios\Generic\Data\Manipulation\Interfaces\SerializationInterface;

/**
 * A message to be logged and supports serialization.
 *
 * @package com.xcitestudios.Logging
 * @subpackage Interfaces
 */
interface LogMessageSerializableInterface
    extends LogMessageInterface, SerializationInterface
{
}