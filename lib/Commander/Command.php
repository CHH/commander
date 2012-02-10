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

    function execute($argv = array())
    {
        $builder = new ProcessBuilder;
        $builder->add($this->executable);

        if (is_array(@$argv[0])) {
            $flags = array_shift($argv);

            foreach ($flags as $flag => $value) {
                $builder->add(
                    (strlen($flag) > 1 ? "--" : "-") . $flag
                );

                $value === true or $builder->add($value);
            }
        }

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

    function __invoke()
    {
        return $this->execute(func_get_args());
    }
}
