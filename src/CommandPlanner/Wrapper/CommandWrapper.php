<?php

namespace CommandPlanner\Wrapper;

use Cron\CronExpression;

/**
 * Class CommandWrapper
 *
 * @package CommandPlanner\Wrapper
 */
class CommandWrapper
{
    /** @var string */
    protected $commandNamespace;

    /** @var CronExpression */
    protected $cronExpression;

    /** @var array */
    protected $parameters;

    /** @var array */
    protected $options;

    /** @var string */
    protected $logFile;

    /** @var string */
    protected $applicationNamespace;

    /**
     * @param string         $commandNamespace
     * @param string         $applicationNamespace
     * @param CronExpression $cronExpression
     * @param array          $commandConfig
     */
    public function __construct($commandNamespace, $applicationNamespace, CronExpression $cronExpression, array $commandConfig)
    {
        if (!isset($commandConfig['parameters']) || !isset($commandConfig['options']) || !isset($commandConfig['log_file'])) {
            throw new \InvalidArgumentException('Invalid command configuration');
        }

        $this->commandNamespace = $commandNamespace;
        $this->applicationNamespace = $applicationNamespace;
        $this->cronExpression = $cronExpression;
        $this->parameters = $commandConfig['parameters'];
        $this->options = $commandConfig['options'];
        $this->logFile = $commandConfig['log_file'];
    }

    /**
     * @return string
     */
    public function getCommandNamespace()
    {
        return $this->commandNamespace;
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

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getLogFile()
    {
        return $this->logFile;
    }

    /**
     * @return string
     */
    public function getApplicationNamespace()
    {
        return $this->applicationNamespace;
    }
}
