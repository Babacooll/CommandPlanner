<?php

namespace CommandPlanner\Pool;

use CommandPlanner\Wrapper\CommandWrapper;

/**
 * Class CommandPool
 *
 * @package CommandPlanner\Pool
 */
class CommandPool
{
    /** @var CommandWrapper[] */
    protected $commandWrappers;

    /**
     * @param CommandWrapper $commandWrapper
     *
     * @return string
     */
    public function add(CommandWrapper $commandWrapper)
    {
        $uniqid = uniqid();

        $this->commandWrappers[$uniqid] = $commandWrapper;

        return $uniqid;
    }

    /**
     * @param string $name
     *
     * @return CommandWrapper
     */
    public function get($name)
    {
        if (!isset($this->commandWrappers[$name])) {
            throw new \InvalidArgumentException(sprintf('Unknow command %s', $name));
        }

        return $this->commandWrappers[$name];
    }

    /**
     * @param string $name
     */
    public function remove($name)
    {
        if (!isset($this->commandWrappers[$name])) {
            throw new \InvalidArgumentException(sprintf('Unknow command %s', $name));
        }

        unset($this->commandWrappers[$name]);
    }

    /**
     * @return CommandWrapper[]
     */
    public function all()
    {
        return $this->commandWrappers;
    }
}
