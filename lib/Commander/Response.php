<?php

namespace Commander;

class Response
{
    protected $output;
    protected $errorOutput;

    function __construct($output, $error)
    {
        $this->output = $output;
        $this->errorOutput = $error;
    }

    function getOutput()
    {
        return $this->output;
    }

    function getErrorOutput()
    {
        return $this->errorOutput;
    }

    function __toString()
    {
        return $this->output;
    }
}

