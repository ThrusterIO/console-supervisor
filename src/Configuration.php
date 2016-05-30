<?php

namespace Thruster\Tool\ConsoleSupervisor;

use Monolog\Logger;

/**
 * Class Configuration
 *
 * @package Thruster\Tool\ConsoleSupervisor
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class Configuration
{
    /**
     * @var string
     */
    private $bin;

    /**
     * @var array
     */
    private $binArguments;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var array
     */
    private $rules;

    public function __construct(string $bin)
    {
        $this->rules = [];
        $this->binArguments = [];

        $this->bin = $bin;
    }

    /**
     * @return string
     */
    public function getBin()
    {
        return $this->bin;
    }

    /**
     * @param string $bin
     *
     * @return $this
     */
    public function setBin($bin)
    {
        $this->bin = $bin;

        return $this;
    }

    /**
     * @return array
     */
    public function getBinArguments()
    {
        return $this->binArguments;
    }

    /**
     * @param array $binArguments
     *
     * @return $this
     */
    public function setBinArguments($binArguments)
    {
        $this->binArguments = $binArguments;

        return $this;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        if (null !== $this->logger) {
            return $this->logger;
        }

        $this->logger = new Logger('supervisor');

        return $this->logger;
    }

    /**
     * @param Logger $logger
     *
     * @return $this
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     *
     * @return $this
     */
    public function setRules($rules)
    {
        $this->rules = $rules;

        return $this;
    }

    public function addRule(Rule $rule)
    {
        $this->rules[$rule->getName()] = $rule;

        return $this;
    }
}
