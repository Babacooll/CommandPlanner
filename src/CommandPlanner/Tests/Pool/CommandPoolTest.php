<?php

namespace CommandPlanner\Tests\Pool;

use CommandPlanner\Pool\CommandPool;
use CommandPlanner\Tests\Data\TestCommand;
use CommandPlanner\Wrapper\CommandWrapper;
use Cron\CronExpression;
use Symfony\Component\Console\Application;

/**
 * Class CommandPoolTest
 *
 * @package CommandPlanner\Tests\Pool
 */
class CommandPoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers CommandPlanner\Pool\CommandPool::add
     */
    public function testAdd()
    {
        $commandPool = new CommandPool();

        $commandConfig = [
            'parameters' => [],
            'options'    => [],
            'log_file'   => []
        ];

        $uniqid = $commandPool->add(new CommandWrapper(get_class(new TestCommand()), get_class(new Application()), CronExpression::factory('* * * * * *'), $commandConfig));

        $this->assertEquals(13, strlen($uniqid));
    }

    /**
     * @covers CommandPlanner\Pool\CommandPool::get
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidGet()
    {
        $commandPool = new CommandPool();

        $commandPool->get(uniqid());
    }

    /**
     * @covers CommandPlanner\Pool\CommandPool::get
     */
    public function testValidGet()
    {
        $commandPool = new CommandPool();

        $commandConfig = [
            'parameters' => [],
            'options'    => [],
            'log_file'   => []
        ];

        $uniqid = $commandPool->add(new CommandWrapper(get_class(new TestCommand()), get_class(new Application()), CronExpression::factory('* * * * * *'), $commandConfig));

        $this->assertInstanceOf('CommandPlanner\Wrapper\CommandWrapper', $commandPool->get($uniqid));
    }

    /**
     * @covers CommandPlanner\Pool\CommandPool::remove
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidRemove()
    {
        $commandPool = new CommandPool();

        $commandPool->remove(uniqid());
    }

    /**
     * @covers CommandPlanner\Pool\CommandPool::remove
     * @expectedException \InvalidArgumentException
     */
    public function testValidRemove()
    {
        $commandPool = new CommandPool();

        $commandConfig = [
            'parameters' => [],
            'options'    => [],
            'log_file'   => []
        ];

        $uniqid = $commandPool->add(new CommandWrapper(get_class(new TestCommand()), get_class(new Application()), CronExpression::factory('* * * * * *'), $commandConfig));

        $commandPool->remove($uniqid);

        $commandPool->get($uniqid);
    }

    /**
     * @covers CommandPlanner\Pool\CommandPool::all
     */
    public function testAll()
    {
        $commandPool = new CommandPool();

        $commandConfig = [
            'parameters' => [],
            'options'    => [],
            'log_file'   => []
        ];

        $commandPool->add(new CommandWrapper(get_class(new TestCommand()), get_class(new Application()), CronExpression::factory('* * * * * *'), $commandConfig));
        $commandPool->add(new CommandWrapper(get_class(new TestCommand()), get_class(new Application()), CronExpression::factory('* * * * * *'), $commandConfig));

        $commandWrappers = $commandPool->all();

        $this->assertCount(2, $commandWrappers);

        foreach ($commandWrappers as $uniqid => $commandWrapper) {
            $this->assertInstanceOf('CommandPlanner\Wrapper\CommandWrapper', $commandWrapper);
            $this->assertEquals(13, strlen($uniqid));
        }
    }
}
