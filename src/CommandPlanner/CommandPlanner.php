<?php

namespace CommandPlanner;

use CommandPlanner\Pool\CommandPool;
use CommandPlanner\Runner\ProcessRunner;
use CommandPlanner\Wrapper\CommandWrapper;
use Cron\CronExpression;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

/**
 * Class CommandPlanner
 *
 * @package CommandPlanner
 */
class CommandPlanner
{
    /** @var Application */
    protected $application;

    /** @var CommandPool */
    protected $commandPool;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $application->setAutoExit(false);

        $this->application = $application;

        $this->commandPool = new CommandPool();
    }

    /**
     * @param Command $command
     * @param string  $timer
     * @param array   $parameters
     *
     * @return $this
     */
    public function add($command, $timer, array $parameters)
    {
        $this->commandPool->add($command, CronExpression::factory($timer), $parameters);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function remove($name)
    {
        $this->commandPool->remove($name);

        return $this;
    }

    /**
     * Runs the command
     */
    public function run()
    {
        foreach ($this->commandPool->all() as $commandWrapper) {
            if ($commandWrapper->getCronExpression()->isDue()) {
                $this->runSubCommand($commandWrapper);
            }
        }
    }

    /**
     * @param CommandWrapper $commandWrapper
     */
    protected function runSubCommand(CommandWrapper $commandWrapper)
    {
        $runner = new ProcessRunner($commandWrapper);

        $runner->runCommandWrapper($commandWrapper);
    }
}
