<?php

namespace CommandPlanner\Tests\Encoder;

use CommandPlanner\Encoder\CommandWrapperEncoder;
use CommandPlanner\Tests\Data\TestCommand;
use CommandPlanner\Wrapper\CommandWrapper;
use Cron\CronExpression;
use Symfony\Component\Console\Application;

/**
 * Class CommandWrapperEncoderTest
 *
 * @package CommandPlanner\Tests\Encoder
 */
class CommandWrapperEncoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers CommandPlanner\Encoder\CommandWrapperEncoder::encode
     */
    public function testEncode()
    {
        $commandConfig = [
            'parameters' => [],
            'options'    => [],
            'log_file'   => []
        ];

        $commandWrapper = new CommandWrapper(get_class(new TestCommand()), get_class(new Application()), CronExpression::factory('* * * * * *'), $commandConfig);

        $encodedCommandWrapper = CommandWrapperEncoder::encode($commandWrapper);

        // Unserialized objects do not reference to the original object
        $this->assertEquals(base64_encode(serialize($commandWrapper)), $encodedCommandWrapper);
    }

    /**
     * @covers CommandPlanner\Encoder\CommandWrapperEncoder::decode
     */
    public function testDecode()
    {
        $commandConfig = [
            'parameters' => [],
            'options'    => [],
            'log_file'   => []
        ];

        $commandWrapper = new CommandWrapper(get_class(new TestCommand()), get_class(new Application()), CronExpression::factory('* * * * * *'), $commandConfig);

        $encodedCommandWrapper = base64_encode(serialize($commandWrapper));
        $decodedCommandWrapper = CommandWrapperEncoder::decode($encodedCommandWrapper);

        // Unserialized objects do not reference to the original object
        $this->assertEquals(unserialize(base64_decode($encodedCommandWrapper)), $decodedCommandWrapper);
    }
}
