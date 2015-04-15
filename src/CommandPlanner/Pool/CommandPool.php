<?php

namespace CommandPlanner\Pool;

use CommandPlanner\Wrapper\CommandWrapper;
use Cron\CronExpression;
use Symfony\Component\Console\Command\Command;

/**
 * Class CommandPool
 *
 * @package CommandPlanner\Pool
 */
class CommandPool
{
    /** @var CommandWrapper[] */
    protected $commandWrappers;

    /**
     * @param Command         $command
     * @param CronExpression  $cronExpression
     * @param array           $parameters
     *
     * @return string
     */
    public function add(Command $command, CronExpression $cronExpression, array $parameters)
    {
        $uniqid = uniqid();

        $this->commandWrappers[$uniqid] = new CommandWrapper($command, $cronExpression, $parameters);

        return $uniqid;
    }

    /**
     * @param string $name
     *
     * @return CommandWrapper
     */
    public function get($name)
    {
        if (!isset($this->commandWrappers[$name])) {
            throw new \InvalidArgumentException(sprintf('Unknow command %s', $name));
        }

        return $this->commandWrappers[$name];
    }

    /**
     * @param string $name
     */
    public function remove($name)
    {
        if (!isset($this->commandWrappers[$name])) {
            throw new \InvalidArgumentException(sprintf('Unknow command %s', $name));
        }

        unset($this->commandWrappers[$name]);
    }

    /**
     * @return CommandWrapper[]
     */
    public function all()
    {
        return $this->commandWrappers;
    }
}
