<?php

use Symfony\Component\Process\ExecutableFinder,
    Commander\Command;

class Commander
{
    protected static $executableFinder;

    # Resolves the command in the current PATH.
    #
    # cmd - Command as String.
    #
    # Examples
    #
    #   echo Commander::which('find');
    #   # => "/usr/bin/find"
    #
    # Returns the absolute path to the command's executable as String.
    static function which($cmd)
    {
        $finder = static::$executableFinder ?: static::$executableFinder = new ExecutableFinder;
        return $finder->find($cmd);
    }

    static function arg($index)
    {
        if (array_key_exists($index, $_SERVER['argv'])) {
            return $_SERVER['argv'][$index];
        }
    }

    # Public: Returns a new command instance, which can be called as function.
    # Use this to call commands when the full path is known.
    #
    # path - Absolute path to the command as String.
    #
    # Returns a Command Object.
    static function command($path)
    {
        return new Command($path);
    }

    static function __callStatic($cmd, $argv)
    {
        $path = static::which($cmd);

        # When the command contains underscores and is not found, then
        # try it one more time with dashes to allow calling `apt-get`
        # as `apt_get()`.
        if (!$path and false !== strpos($cmd, '_')) {
            $cmd = str_replace('_', '-', $cmd);
            $path = static::which($cmd);
        }

        if (!$path) {
            throw new \UnexpectedValueException("Command not found.");
        }

        $cmd = static::command($path);
        return $cmd->execute($argv);
    }
}
