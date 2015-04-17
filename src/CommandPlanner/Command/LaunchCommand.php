<?php

namespace CommandPlanner\Command;

use CommandPlanner\Encoder\CommandWrapperEncoder;
use CommandPlanner\Wrapper\CommandWrapper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

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
            ->setDescription('Greet someone')
            ->addArgument(
                'subCommandArguments',
                InputArgument::REQUIRED,
                'Who do you want to greet?'
            )
        ;
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

        $commandNamespace = $commandWrapper->getCommandNamespace();

        /** @var Command $subCommand */
        $subCommand = new $commandNamespace();

        $subApplicationNamespace = $commandWrapper->getApplicationNamespace();

        /** @var Application $subApplication */
        $subApplication = new $subApplicationNamespace();

        $subApplication->setAutoExit(false);

        $subApplication->add($subCommand);

        $handle = fopen($commandWrapper->getLogFile(), 'a+');

        $arguments = $this->getArguments($subCommand, $commandWrapper);
        $output->writeln('Launch command : ' . $arguments[1]);

        $subApplication->run(new ArgvInput($arguments), new StreamOutput($handle));

        fclose($handle);
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
}
