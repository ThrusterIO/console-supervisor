<?php

namespace Thruster\Tool\ConsoleSupervisor;

/**
 * Class Command
 *
 * @package Thruster\Tool\ConsoleSupervisor
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class Command
{
    /**
     * @var array
     */
    private $arguments;

    /**
     * @var string
     */
    private $originalBin;

    /**
     * @var string
     */
    private $bin;

    /**
     * @var string
     */
    private $command;

    public function __construct(Configuration $configuration)
    {
        $this->arguments = $_SERVER['argv'];
        $this->originalBin = array_shift($this->arguments);

        $this->arguments = array_merge(
            $this->arguments,
            $configuration->getBinArguments()
        );

        $this->bin = $configuration->getBin();
    }

    public function getFinalArguments()
    {
        $arguments = $this->arguments;

        array_unshift($arguments, $this->bin);

        return $arguments;
    }

    public function getString()
    {
        if (null === $this->command) {
            $this->command = implode(' ', $this->arguments);
        }

        return $this->command;
    }

    /**
     * @return string
     */
    public function getOriginalBin()
    {
        return $this->originalBin;
    }

    /**
     * @return string
     */
    public function getBin()
    {
        return $this->bin;
    }
}
