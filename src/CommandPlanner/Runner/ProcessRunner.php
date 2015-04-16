<?php

namespace CommandPlanner\Runner;

use CommandPlanner\Wrapper\CommandWrapper;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;

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
        // Pass command namespace to LaunchApplication
        $arguments = [get_class($commandWrapper->getCommand())];

        // Merge command parameters to command namespace
        $arguments = array_merge($arguments, $commandWrapper->getParameters());

        // Launch sub command
        $process = new Process($this->buildBashCommand($arguments), $backgroundRun);

        $process->run();

        if (!$backgroundRun) {
            $output = new ConsoleOutput();

            $output->writeln('<bg=green;options=bold;fg=black>' . $process->getOutput() . '</bg=green;options=bold;fg=black>');
        }
    }

    /**
     * @param array $arguments
     * @param bool  $backgroundRun
     *
     * @return string
     */
    protected function buildBashCommand($arguments, $backgroundRun = false)
    {
        $command = 'php ' . __DIR__ . '/../../../bin/launcher ' . base64_encode(serialize($arguments));

        if ($backgroundRun) {
            $command .= ' > /dev/null 2>/dev/null &';
        }

        return $command;
    }
}
