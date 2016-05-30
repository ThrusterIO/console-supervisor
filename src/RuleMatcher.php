<?php

namespace Thruster\Tool\ConsoleSupervisor;

/**
 * Class RuleMatcher
 *
 * @package Thruster\Tool\ConsoleSupervisor
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class RuleMatcher
{
    /**
     * @var Rule[]
     */
    private $rules;

    public function __construct(Configuration $configuration)
    {
        $this->rules = $configuration->getRules();
    }

    public function matchRules(Command $command)
    {
        foreach ($this->rules as $name => $rule) {
            if (preg_match('/' . $rule->getPattern() . '/', $command->getString())) {
                $rule->setCommand($command);

                return $rule;
            }
        }

        $rule = new Rule('unknown');
        $rule->setCommand($command);

        return $rule;
    }
}
