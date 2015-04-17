<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CommandPlanner\Output;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\Output;

/**
 * StreamOutput writes the output to a given stream.
 *
 * Usage:
 *
 * $output = new StreamOutput(fopen('php://stdout', 'w'));
 *
 * As `StreamOutput` can use any stream, you can also use a file:
 *
 * $output = new StreamOutput(fopen('/path/to/output.log', 'a', false));
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
class LoggerOutput extends Output
{
    private $logger;

    /**
     * Constructor.
     *
     * @param LoggerInterface               $logger
     * @param int                           $verbosity The verbosity level (one of the VERBOSITY constants in OutputInterface)
     * @param bool|null                     $decorated Whether to decorate messages (null for auto-guessing)
     * @param OutputFormatterInterface|null $formatter Output formatter instance (null to use default OutputFormatter)
     *
     * @throws \InvalidArgumentException When first argument is not a real stream
     *
     * @api
     */
    public function __construct(LoggerInterface $logger, $verbosity = self::VERBOSITY_NORMAL, $decorated = null, OutputFormatterInterface $formatter = null)
    {
        $this->logger = $logger;

        if (null === $decorated) {
            $decorated = $this->hasColorSupport();
        }

        parent::__construct($verbosity, $decorated, $formatter);
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * {@inheritdoc}
     */
    protected function doWrite($message, $newline)
    {
        $this->logger->info($message);
    }

    /**
     * Returns true if the stream supports colorization.
     *
     * Colorization is disabled if not supported by the stream:
     *
     *  -  Windows without Ansicon and ConEmu
     *  -  non tty consoles
     *
     * @return bool true if the stream supports colorization, false otherwise
     */
    protected function hasColorSupport()
    {
        return false;
    }
}
