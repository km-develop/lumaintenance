<?php

namespace Lumaintenance\Exception;

/**
 * Class MaintenanceDownException
 * @package Lumaintenance\Exception
 */
class MaintenanceDownException extends \Exception
{
    protected $message = 'Failed to release maintenance.';
}
