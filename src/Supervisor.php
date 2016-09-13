<?php

namespace Thruster\Tool\ConsoleSupervisor;

use Monolog\Logger;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class Supervisor
 *
 * @package Thruster\Tool\ConsoleSupervisor
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class Supervisor
{
    /**
     * @var Configuration
     *
     * @return int
     */
    private $configuration;

    public function start($configFile)
    {
        $this->configuration = include_once $configFile;

        return $this->startProcess();
    }

    /**
     * @param Rule $rule
     *
     * @return Logger
     */
    private function loadLogger(Rule $rule)
    {
        $logger = $rule->getLogger();

        if (null !== $logger) {
            return $logger;
        }

        return $this->configuration->getLogger();
    }

    private function startProcess()
    {
        $command = new Command($this->configuration);
        $ruleMatcher = new RuleMatcher($this->configuration);

        $rule = $ruleMatcher->matchRules($command);

        $logger = $this->loadLogger($rule);

        $processBuilder = new ProcessBuilder($rule, $this->configuration);

        for ($i = 0; $i <= $rule->getRetries(); $i++) {
            if ($i > 0) {
                $logger->warning(
                    sprintf(
                        'Retrying command %s time after failure',
                        $i
                    ),
                    ['args' => $command->getString(), 'bin' => $command->getBin()]
                );
            }

            $logger->info('Starting command', ['args' => $command->getString(), 'bin' => $command->getBin()]);

            $process = $processBuilder->getProcess();

            $stdOut = fopen('php://memory', 'r+');
            $stdErr = fopen('php://memory', 'r+');

            try {

                $exitCode = $process->run(function ($type, $buffer) use ($stdErr, $stdOut) {
                    if (Process::ERR === $type) {
                        fwrite($stdErr, $buffer);
                        fwrite(STDERR, $buffer);
                    } else {
                        fwrite($stdOut, $buffer);
                        fwrite(STDOUT, $buffer);
                    }
                });

                if ($process->isSuccessful() || in_array($process->getExitCode(), $this->configuration->getSuccessCondes())) {
                    $logger->info(
                        'Command exited successfully',
                        ['args' => $command->getString(), 'bin' => $command->getBin()]
                    );

                    $logger->info('Finished command', ['args' => $command->getString(), 'bin' => $command->getBin()]);

                    return $exitCode;
                } else {
                    $logger->error(
                        'Command exited unsuccessfully',
                        [
                            'exit'   => [
                                'code'    => $process->getExitCode(),
                                'message' => $process->getExitCodeText()
                            ],
                            'output' => [
                                'stdout' => $this->getStreamContent($stdOut),
                                'stderr' => $this->getStreamContent($stdErr)
                            ],
                            'args'   => $command->getString(),
                            'bin'    => $command->getBin()
                        ]
                    );
                }
            } catch (ProcessFailedException $e) {
                $logger->error(
                    'Could not start command',
                    [
                        'message' => $e->getMessage(),
                        'args'    => $command->getString(),
                        'bin'     => $command->getBin()
                    ]
                );
            }

            fclose($stdOut);
            fclose($stdErr);

            if ($i < $rule->getRetries()) {
                $logger->warn(
                    sprintf(
                        'Waiting for %s seconds before retrying',
                        $rule->getRetryDelay()
                    ),
                    [
                        'retry' => $i,
                        'retries' => $rule->getRetries(),
                        'args' => $command->getString(),
                        'bin' => $command->getBin()
                    ]
                );
            }
        }

        return $exitCode;
    }

    private function getStreamContent($resource)
    {
        rewind($resource);

        return preg_replace('/[^(\x20-\x7F)]*/', '', stream_get_contents($resource));
    }
}
