<?php

namespace CommandPlanner\Runner;

use CommandPlanner\Encoder\CommandWrapperEncoder;
use CommandPlanner\Wrapper\CommandWrapper;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class ProcessRunner
 *
 * @package CommandPlanner\Runner
 */
class ProcessRunner
{
    /**
     * @param CommandWrapper $commandWrapper
     * @param bool           $backgroundRun
     */
    public function runCommandWrapper(CommandWrapper $commandWrapper, $backgroundRun = false)
    {
        // Launch sub command
        $process = $this->buildBashCommand($commandWrapper);

        $process->run();

        if (!$backgroundRun) {
            $output = new ConsoleOutput();

            $output->writeln('<options=bold>' . $process->getOutput() . '</options=bold;>');
        }
    }

    /**
     * @param CommandWrapper $commandWrapper
     * @param bool           $backgroundRun
     *
     * @return Process
     */
    protected function buildBashCommand(CommandWrapper $commandWrapper, $backgroundRun = false)
    {
        $finder = new PhpExecutableFinder();
        $php = $finder->find();

        $processBuilder = new ProcessBuilder();

        $process = $processBuilder
            ->add($php)
            ->add(__DIR__ . '/../../../bin/launcher')
            ->add(CommandWrapperEncoder::encode($commandWrapper));

        if ($backgroundRun) {
            $process->add('> /dev/null 2>/dev/null &');
        }

        return $process->getProcess();
    }
}
