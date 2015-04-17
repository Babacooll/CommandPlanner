<?php

namespace CommandPlanner\Encoder;

use CommandPlanner\Wrapper\CommandWrapper;

/**
 * Class CommandWrapperEncoder
 *
 * @package CommandPlanner\Encoder
 */
class CommandWrapperEncoder
{
    /**
     * @param CommandWrapper $commandWrapper
     *
     * @return string
     */
    public static function encode(CommandWrapper $commandWrapper)
    {
        return base64_encode(serialize($commandWrapper));
    }

    /**
     * @param string $encodedCommandWrapper
     *
     * @return CommandWrapper
     */
    public static function decode($encodedCommandWrapper)
    {
        return unserialize(base64_decode($encodedCommandWrapper));
    }
}
