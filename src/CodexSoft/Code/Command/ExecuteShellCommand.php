<?php

namespace CodexSoft\Code\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for generating new blank migration classes
 */
class ExecuteShellCommand extends Command
{

    /** @var string[] */
    private $cmds;

    /** @var bool */
    private $stopOnError;

    public function __construct(array $cmds, string $name = null, $stopOnError = false)
    {
        parent::__construct($name);
        $this->cmds = $cmds;
        $this->setDescription('Executes shell scripts');
        $this->stopOnError = $stopOnError;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->cmds as $cmd) {
            $out = [];
            $output->writeln("Executing script $cmd");
            exec(escapeshellcmd($cmd), $out, $code);

            foreach($out as $line) {
                $output->writeln($line, \Symfony\Component\Console\Output\OutputInterface::VERBOSITY_VERY_VERBOSE);
            }

            if (((int) $code !== 0) && $this->stopOnError) {
                throw new \Exception('Command '.$cmd.' failed with exit code '.$code.'. Further commands executing cancelled.');
            }
        }
    }

}
