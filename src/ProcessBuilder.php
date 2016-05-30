<?php

namespace Thruster\Tool\ConsoleSupervisor;

use Symfony\Component\Process\ProcessBuilder as BaseProcessBuilder;

/**
 * Class ProcessBuilder
 *
 * @package Thruster\Tool\ConsoleSupervisor
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class ProcessBuilder extends BaseProcessBuilder
{
    /**
     * @inheritDoc
     */
    public function __construct(Rule $rule, Configuration $configuration)
    {
        parent::__construct($rule->getCommand()->getFinalArguments());

        $this->setTimeout(null);
        $this->inheritEnvironmentVariables(true);
    }

}
