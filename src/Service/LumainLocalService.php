<?php

namespace Lumaintenance\Service;

use Illuminate\Support\Carbon;
use Lumaintenance\Entity\IP;
use Lumaintenance\Exception\MaintenanceDownException;
use Lumaintenance\Exception\MaintenanceUpException;

/**
 * Class LumainLocalService
 * @package Lumaintenance\Service
 */
class LumainLocalService implements LumainLocalServiceInterface
{
    /**
     * @var LumainConfigInterface
     */
    private $lumainConfig;

    /**
     * LumainLocalService constructor.
     * @param LumainConfigInterface $lumainConfig
     */
    public function __construct(
        LumainConfigInterface $lumainConfig
    )
    {
        $this->lumainConfig = $lumainConfig;
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function isDown(): bool
    {
        $path = storage_path($this->lumainConfig->getLocalFile());
        throw_if(is_file($path) && !is_readable($path), MaintenanceUpException::class);
        return is_file($path);
    }

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function up()
    {
        if ($this->isDown()) {
            $path = storage_path($this->lumainConfig->getLocalFile());
            throw_if(!unlink($path), MaintenanceUpException::class);
        }
    }

    /**
     * @param array $allowIPs
     * @return mixed|void
     * @throws \Throwable
     */
    public function down(array $allowIPs)
    {
        $content = [
            'allows' => $allowIPs,
            'updated_at' => Carbon::now()->unix()
        ];
        $path = storage_path($this->lumainConfig->getLocalFile());
        $result = file_put_contents($path, \json_encode($content));
        throw_if(false === $result, MaintenanceDownException::class);
    }

    /**
     * @return IP
     */
    public function allowIP(): IP
    {
        $ip = [];
        if ($this->isDown()) {
            // file allow ip
            $path = storage_path($this->lumainConfig->getLocalFile());
            $downJSON = file_get_contents($path);
            $downMeta = \json_decode($downJSON, true);
            $localIP = $downMeta['allows'];

            // env allow ip
            $envIP = $this->lumainConfig->getEnvAllowIPS();
            $ip = array_merge($localIP, $envIP);
        }
        return new IP(empty($ip) ? [] : $ip);
    }
}
