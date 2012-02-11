<?php

namespace Commander;

class ErrorException extends Exception
{
    protected $errorOutput = "";

    function __construct($msg, $code, $errorOutput = "")
    {
        $this->message = $msg;
        $this->code = $code;
        $this->errorOutput = $errorOutput;
    }

    function getErrorOutput()
    {
        return $this->errorOutput;
    }
}
