<?php

namespace CommandPlanner\Runner;

use CommandPlanner\Wrapper\CommandWrapper;

/**
 * Interface RunnerInterface
 *
 * @package CommandPlanner\Runner
 */
interface RunnerInterface
{
    /**
     * @param CommandWrapper $commandWrapper
     */
    public function runCommandWrapper(CommandWrapper $commandWrapper);
}
