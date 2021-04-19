<?php

namespace Lumaintenance\Console;

use Illuminate\Console\Command;
use Lumaintenance\Exception\InvalidArgumentException;
use Lumaintenance\Service\LumainLocalService;

/**
 * Class LocalStorageMaintenanceCommand
 * @package Lumaintenance\Console
 */
class LocalStorageCommand extends Command
{
    protected $signature = 'lumain:local {action : up or down} {--allow= : Enter the IP addresses to be allowed, separated by commas.}';
    protected $description = 'Performs maintenance operations using local files';

    /**
     * @var LumainLocalService
     */
    private $lumainLocalService;

    /**
     * MaintenanceStatusCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->lumainLocalService = app()->make(LumainLocalService::class);
    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        $action = $this->argument('action');
        $allow = $this->option('allow');

        if ('up' === $action) {
            $this->lumainLocalService->up();
        } elseif ('down' === $action) {
            $allow = empty($allow) ? [] : explode(',', $allow);
            $this->lumainLocalService->down($allow);
        } else {
            throw new InvalidArgumentException;
        }
    }
}
