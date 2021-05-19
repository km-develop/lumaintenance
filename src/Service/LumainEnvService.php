<?php

namespace Lumaintenance\Service;

use Lumaintenance\Entity\IP;
use Lumaintenance\Exception\UnImplementedException;

/**
 * Class LumainEnvService
 * @package Lumaintenance\Service
 */
class LumainEnvService implements LumainEnvServiceInterface
{
    /**
     * @var LumainConfigInterface
     */
    private $lumainConfig;

    /**
     * LumainEnvService constructor.
     * @param LumainConfigInterface $lumainConfig
     */
    public function __construct(
        LumainConfigInterface $lumainConfig
    ) {
        $this->lumainConfig = $lumainConfig;
    }

    /**
     * @return bool
     */
    public function isDown(): bool
    {
        return $this->lumainConfig->getEnvISDown();
    }

    /**
     * @return mixed|void
     */
    public function up()
    {
        throw new UnImplementedException();
    }

    /**
     * @param array $allowIPs
     * @return mixed|void
     */
    public function down(array $allowIPs)
    {
        throw new UnImplementedException();
    }

    /**
     * @return IP
     */
    public function allowIP(): IP
    {
        return new IP($this->lumainConfig->getEnvAllowIPS());
    }
}
