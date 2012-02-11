<?php

namespace Commander\Test;

use Commander as cmd,
    Commander\ErrorException;

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
        $stdout = (string) cmd::ls();
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
        $this->assertEquals("foo bar baz\n", (string) $args);
    }

    function testFlagsPassedAsArray()
    {
        $args = cmd::echo_args(array('foo' => 'bar', 'f' => true));
        $this->assertEquals("--foo bar -f\n", (string) $args);
    }

    function testFlagsPassedAsArgument()
    {
        $args = cmd::echo_args("-rf", "-a");
        $this->assertEquals("-rf -a\n", (string) $args);
    }

    function testPiping()
    {
        $res = cmd::wc(cmd::echo_args("foo"), "-c");

        $this->assertEquals("4", trim((string) $res));
    }

    function testCommandThrowsErrorExceptionWhenCommandExitsWithError()
    {
        $false = cmd::command(cmd::which('false'));

        try {
            $false();

        } catch (ErrorException $e) {
            $this->assertEquals(1, $e->getCode());
            return;
        }

        $this->fail("Expected Exception Commander\ErrorException");
    }

    function testErrorExceptionHasErrorOutput()
    {
        try {
            cmd::ls('--foo');

        } catch (ErrorException $e) {
            $this->assertEquals(1, $e->getCode());
            $this->assertNotEmpty($e->getErrorOutput());
            return;
        }

        $this->fail("Expected Exception Commander\ErrorException");
    }
}
