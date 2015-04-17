<?php

namespace CommandPlanner\Pool;

use CommandPlanner\Wrapper\CommandWrapper;
use Cron\CronExpression;

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
     * @param string         $commandNamespace
     * @param CronExpression $cronExpression
     * @param array          $commandConfig
     * @param string         $applicationNamespace
     *
     * @return string
     */
    public function add($commandNamespace, CronExpression $cronExpression, array $commandConfig, $applicationNamespace)
    {
        $uniqid = uniqid();

        $this->commandWrappers[$uniqid] = new CommandWrapper($commandNamespace, $cronExpression, $commandConfig, $applicationNamespace);

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
