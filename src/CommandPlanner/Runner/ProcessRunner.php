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
        // Launch sub command
        $process = new Process($this->buildBashCommand($commandWrapper), $backgroundRun);

        $process->run();

        if (!$backgroundRun) {
            $output = new ConsoleOutput();

            $output->writeln('<bg=green;options=bold;fg=black>' . $process->getOutput() . '</bg=green;options=bold;fg=black>');
        }
    }

    /**
     * @param CommandWrapper $commandWrapper
     * @param bool           $backgroundRun
     *
     * @return string
     */
    protected function buildBashCommand(CommandWrapper $commandWrapper, $backgroundRun = false)
    {
        $command = 'php ' . __DIR__ . '/../../../bin/launcher ' . base64_encode(serialize($commandWrapper));

        if ($backgroundRun) {
            $command .= ' > /dev/null 2>/dev/null &';
        }

        return $command;
    }
}
