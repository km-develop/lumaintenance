<?php

namespace Lumaintenance\Exception;

/**
 * Class InvalidArgumentException
 * @package Lumaintenance\Exception
 */
class InvalidArgumentException extends \Exception
{
    protected $message = 'The argument is not the expected value.';
}
