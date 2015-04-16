<?php

namespace CommandPlanner\Command;

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
        /** @var CommandWrapper $commandWrapper */
        $commandWrapper = unserialize(base64_decode($input->getArgument('subCommandArguments')));

        $commandNamespace = $commandWrapper->getCommandNamespace();
        /** @var Command $subCommand */
        $subCommand     = new $commandNamespace();

        $arguments      = array_merge($commandWrapper->getParameters(), $commandWrapper->getOptions());
        $arguments      = array_merge([null, $subCommand->getName()], $arguments);

        $subApplicationNamespace = $commandWrapper->getApplicationNamespace();
        /** @var Application $subApplication */
        $subApplication = new $subApplicationNamespace();

        $subApplication->add($subCommand);

        $handle = fopen($commandWrapper->getLogFile(), 'a+');

        $output->write('Launch command : ' . $arguments[1]);

        $subApplication->run(new ArgvInput($arguments), new StreamOutput($handle));

        fclose($handle);
    }
}
