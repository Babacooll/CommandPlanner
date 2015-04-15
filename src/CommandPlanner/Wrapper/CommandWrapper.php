<?php

namespace CommandPlanner\Wrapper;

use Cron\CronExpression;
use Symfony\Component\Console\Command\Command;

/**
 * Class CommandWrapper
 *
 * @package CommandPlanner\Wrapper
 */
class CommandWrapper
{
    /** @var Command */
    protected $command;

    /** @var CronExpression */
    protected $cronExpression;

    /** @var array */
    protected $parameters;

    /**
     * @param Command         $command
     * @param CronExpression  $cronExpression
     * @param array           $parameters
     */
    public function __construct(Command $command, CronExpression $cronExpression, array $parameters)
    {
        $this->command = $command;
        $this->cronExpression = $cronExpression;
        $this->parameters = $parameters;
    }

    /**
     * @return Command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return CronExpression
     */
    public function getCronExpression()
    {
        return $this->cronExpression;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
