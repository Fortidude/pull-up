<?php

namespace PullUpDomain\Exception;

class DuplicateEntityException extends \Exception
{
    public function __construct($message, $code, \Exception $previous)
    {
        parent::__construct($message, 400, $previous);
    }
}