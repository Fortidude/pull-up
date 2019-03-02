<?php

namespace PullUpBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class BadRequest extends HttpException
{
    public function __construct($message)
    {
        parent::__construct(400, $message, null, [], 400);
    }
}