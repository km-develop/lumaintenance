<?php

namespace Lumaintenance\Console;

use Illuminate\Console\Command;
use Lumaintenance\Service\LumainEnvService;
use Lumaintenance\Service\LumainLocalService;

/**
 * Class MaintenanceStatusCommand
 * @package Lumaintenance\Console
 */
class MaintenanceStatusCommand extends Command
{
    protected $signature = 'lumain';
    protected $description = 'Displays information about the current maintenance.';

    /**
     * @var LumainEnvService
     */
    private $lumainEnvService;

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
        $this->lumainEnvService = app()->make(LumainEnvService::class);
        $this->lumainLocalService = app()->make(LumainLocalService::class);
    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        $headers = ['mode', 'status', 'Allow IP'];

        $envStatus = $this->lumainEnvService->isDown() ? 'down' : 'up';
        $envIP = $this->lumainEnvService->allowIP()->toString();

        $localStatus = $this->lumainLocalService->isDown() ? 'down' : 'up';
        $localIP = $this->lumainLocalService->allowIP()->toString();

        $this->table($headers, [
            ['env', $envStatus, $envIP],
            ['local', $localStatus, $localIP]
        ]);

        /* Show Warning */
        // env: down && No whitelist
        if ($this->lumainEnvService->isDown() && $envIP === '-') {
            $this->warn('env maintenance is down, but there is no whitelist of IPs');
        }
        // local: down && No whitelist
        if ($this->lumainLocalService->isDown() && $localIP === '-') {
            $this->warn('local maintenance is down, but there is no whitelist of IPs');
        }
    }
}
