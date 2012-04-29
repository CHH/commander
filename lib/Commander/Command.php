<?php

namespace Commander;

use Symfony\Component\Process\ProcessBuilder;

class Command
{
    protected $executable;

    function __construct($executable)
    {
        if (!file_exists($executable)) {
            throw new CommandNotFoundException("Command $executable not found.");
        }
        $this->executable = $executable;
    }

    # Executes the command with the list of arguments.
    #
    # argv - List of arguments.
    #
    # Returns the process Standard Output as String.
    function execute($argv = array())
    {
        $builder = new ProcessBuilder;
        $builder->add($this->executable);

        if (@$argv[0] instanceof Response) {
            $builder->setInput(array_shift($argv)->getOutput());

        } else if (is_array(@$argv[0])) {
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
            $commandLine = $process->getCommandLine();

            throw new ErrorException(
                "Command [$commandLine] failed with status [$status].", 
                $status, $process->getErrorOutput()
            );
        }

        return new Response($process->getOutput(), $process->getErrorOutput());
    }

    function __invoke()
    {
        return $this->execute(func_get_args());
    }
}
