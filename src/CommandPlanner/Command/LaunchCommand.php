<?php

namespace CommandPlanner\Command;

use CommandPlanner\Encoder\CommandWrapperEncoder;
use CommandPlanner\Logger\FileLogger;
use CommandPlanner\Output\LoggerOutput;
use CommandPlanner\Wrapper\CommandWrapper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LaunchCommand
 *
 * @package CommandPlanner\Command
 */
class LaunchCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('commandplanner:launch')
            ->setDescription('Launches sub user command')
            ->addArgument(
                'subCommandArguments',
                InputArgument::REQUIRED
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandWrapper = CommandWrapperEncoder::decode($input->getArgument('subCommandArguments'));
        $subCommand =  $this->getCommandFromNamespace($commandWrapper->getCommandNamespace());
        $subApplication = $this->getApplicationFromNamespace($commandWrapper->getApplicationNamespace());

        $subApplication->add($subCommand);

        $arguments = $this->getArguments($subCommand, $commandWrapper);

        $output->writeln('--------');
        $output->writeln('[Command] ' . $subCommand->getName());

        $logger = new FileLogger($commandWrapper->getLogFile());

        $exitCode = $subApplication->run(new ArgvInput($arguments), new LoggerOutput($logger));

        if ($exitCode == 0) {
            $output->writeln('-> Success');
        } else {
            $output->writeln('-> Failure');
        }
        $output->writeln('--------');
    }

    /**
     * @param Command        $command
     * @param CommandWrapper $commandWrapper
     *
     * @return array
     */
    protected function getArguments(Command $command, CommandWrapper $commandWrapper)
    {
        $arguments = array_merge($commandWrapper->getParameters(), $commandWrapper->getOptions());

        return array_merge([null, $command->getName()], $arguments);
    }

    /**
     * @param string $namespace
     *
     * @return Application
     */
    protected function getApplicationFromNamespace($namespace)
    {
        $application = new $namespace();

        if (!$application instanceof Application) {
            throw new \InvalidArgumentException('Invalid application');
        }

        $application->setAutoExit(false);

        return $application;
    }

    /**
     * @param string $namespace
     *
     * @return Command
     */
    protected function getCommandFromNamespace($namespace)
    {
        $command = new $namespace();

        if (!$command instanceof Command) {
            throw new \InvalidArgumentException('Invalid command');
        }

        return $command;
    }
}
