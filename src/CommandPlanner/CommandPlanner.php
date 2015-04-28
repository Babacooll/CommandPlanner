<?php

namespace CommandPlanner;

use CommandPlanner\Config\Configuration;
use CommandPlanner\FileLoader\YamlConfigLoader;
use CommandPlanner\Pool\CommandPool;
use CommandPlanner\Runner\ProcessRunner;
use CommandPlanner\Wrapper\CommandWrapper;
use Cron\CronExpression;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Console\Application;

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

    /** @var array */
    protected $config;

    /**
     * Init CommandPool
     */
    public function __construct()
    {
        $this->commandPool = new CommandPool();
    }

    /**
     * @param string $configExpression
     *
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     */
    public function addCommandsFromConfig($configExpression)
    {
        $configFile       = basename($configExpression);
        $configDirectory  = dirname($configExpression);

        $locator          = new FileLocator([$configDirectory]);
        $loaderResolver   = new LoaderResolver([new YamlConfigLoader($locator)]);
        $delegatingLoader = new DelegatingLoader($loaderResolver);
        $config           = $delegatingLoader->load($locator->locate($configFile));
        $processor        = new Processor();
        $configuration    = new Configuration();
        $processedConfiguration = $processor->processConfiguration(
            $configuration,
            $config
        );

        $this->config = $processedConfiguration;

        /** @var Application $application */
        $this->application = new $this->config['application']();

        $this->application->setAutoExit(false);;

        foreach ($this->config['commands'] as $command) {
            $this->add(new CommandWrapper($command['namespace'], $this->config['application'], CronExpression::factory($command['timing']), $command));
        }
    }

    /**
     * @param CommandWrapper $commandWrapper
     *
     * @return CommandPlanner
     */
    public function add(CommandWrapper $commandWrapper)
    {
        $this->commandPool->add($commandWrapper);

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
