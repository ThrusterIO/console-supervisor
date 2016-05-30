<?php

namespace Thruster\Tool\ConsoleSupervisor;

use Monolog\Logger;

/**
 * Class Rule
 *
 * @package Thruster\Tool\ConsoleSupervisor
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class Rule
{
    /**
     * @var Command
     */
    private $command;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var int
     */
    private $retries;

    /**
     * @var int
     */
    private $retryDelay;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(
        $name = '',
        $pattern = ''
    ) {
        $this->name = $name;
        $this->pattern = $pattern;
        $this->retries = 0;
        $this->retryDelay = 0;
    }

    /**
     * @return Command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @return int
     */
    public function getRetries()
    {
        return $this->retries;
    }

    /**
     * @return int
     */
    public function getRetryDelay()
    {
        return $this->retryDelay;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Command $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param int $retries
     */
    public function setRetries($retries)
    {
        $this->retries = $retries;
    }

    /**
     * @param int $retryDelay
     */
    public function setRetryDelay($retryDelay)
    {
        $this->retryDelay = $retryDelay;
    }
}
