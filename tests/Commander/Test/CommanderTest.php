<?php

namespace Commander\Test;

use Commander as cmd;

class CommanderTest extends \PHPUnit_Framework_TestCase
{
    function __construct()
    {
        putenv("PATH=".join(PATH_SEPARATOR, array(
            dirname(dirname(__DIR__)), getenv("PATH")
        )));

        parent::__construct();
    }

    function testReturnsStdOut()
    {
        $stdout = cmd::ls();
        $this->assertFalse(empty($stdout));
    }

    function testWhichReturnsAbsolutePath()
    {
        $path = cmd::which('find');
        $this->assertEquals('/usr/bin/find', $path);
    }

    function testPassesFunctionArgumentsAsCommandArguments()
    {
        $args = cmd::echo_args("foo", "bar", "baz");
        $this->assertEquals("foo bar baz\n", $args);
    }

    function testPassesOptionsAsFlags()
    {
        $args = cmd::echo_args(array('foo' => 'bar', 'f' => true));
        $this->assertEquals("--foo bar -f\n", $args);
    }
}
