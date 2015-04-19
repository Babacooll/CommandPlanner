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

    /**
     * @param string      $configDirectory
     * @param string      $configFile
     *
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     */
    public function __construct($configDirectory, $configFile)
    {
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

        $this->application->setAutoExit(false);

        $this->initCommandsFromConfig();
    }

    /**
     * @param string  $commandNamespace
     * @param string  $timer
     * @param array   $commandConfig
     *
     * @return $this
     */
    public function add($commandNamespace, $timer, array $commandConfig)
    {
        $this->commandPool->add($commandNamespace, $this->config['application'], CronExpression::factory($timer), $commandConfig);

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

    /**
     * Init commands from the config file
     */
    protected function initCommandsFromConfig()
    {
        $this->commandPool = new CommandPool();

        foreach ($this->config['commands'] as $command) {
            $this->add($command['namespace'], $command['timing'], $command);
        }
    }
}
