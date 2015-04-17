<?php

namespace CommandPlanner\Logger;

use Psr\Log\AbstractLogger;

/**
 * Class FileLogger
 *
 * @package CommandPlanner\Logger
 */
class FileLogger extends AbstractLogger
{
    /** @var resource */
    protected $handler;

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        $this->handler = fopen($file, 'a+');
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        $message = $this->getFormattedMessage($level, $message);

        if (false === @fwrite($this->handler, $message)) {
            // should never happen
            throw new \RuntimeException('Unable to write output.');
        }

        fflush($this->handler);
    }

    /**
     * @param string $level
     * @param string $message
     *
     * @return string
     */
    protected function getFormattedMessage($level, $message)
    {
        return '[' . ucfirst($level) . ' - ' . date('d-m-Y H:i:s') . '] ' . $message . PHP_EOL;
    }
}
