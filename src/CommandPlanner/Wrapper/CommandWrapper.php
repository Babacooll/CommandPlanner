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
     * @param CronExpression $cronExpression
     * @param array          $commandConfig
     * @param string         $applicationNamespace
     */
    public function __construct($commandNamespace, CronExpression $cronExpression, array $commandConfig, $applicationNamespace)
    {
        $this->commandNamespace = $commandNamespace;
        $this->cronExpression = $cronExpression;
        $this->parameters = $commandConfig['parameters'];
        $this->options = $commandConfig['options'];
        $this->logFile = $commandConfig['log_file'];
        $this->applicationNamespace = $applicationNamespace;
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
