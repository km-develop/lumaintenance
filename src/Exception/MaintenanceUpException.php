<?php

namespace Lumaintenance\Exception;

/**
 * Class MaintenanceUpException
 * @package Lumaintenance\Exception
 */
class MaintenanceUpException extends \Exception
{
    protected $message = 'Failed to set up maintenance.';
}
