<?php

namespace CommandPlanner\Command;

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
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arguments      = unserialize(base64_decode($input->getArgument('subCommandArguments')));

        /** @var Command $subCommand */
        $subCommand     = new $arguments[0]();

        array_shift($arguments);

        $arguments      = array_merge([null, $subCommand->getName()], $arguments);

        $subApplication = new Application();
        $subApplication->add($subCommand);

        $subApplication->run(new ArgvInput($arguments));

        exit;
    }
}
