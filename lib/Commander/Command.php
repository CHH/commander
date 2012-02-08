<?php

namespace Commander;

use Symfony\Component\Process\ProcessBuilder;

class Command
{
    protected $executable;

    function __construct($executable)
    {
        $this->executable = $executable;
    }

    function __invoke()
    {
        $argv = func_get_args();

        $builder = new ProcessBuilder;
        $builder->add($this->executable);

        foreach ($argv as $a) {
            $builder->add($a);
        }

        $process = $builder->getProcess();
        $process->run();

        if (!$process->isSuccessful()) {
            $status = $process->getExitCode();
            throw new Exception("Command failed with status [$status].", $status);
        }

        return $process->getOutput();
    }
}
