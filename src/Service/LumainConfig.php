<?php

namespace Lumaintenance\Service;

/**
 * Class LumainConfig
 * @package Lumaintenance\Service
 */
class LumainConfig implements LumainConfigInterface
{
    /**
     * @var LumainConfigInterface
     */
    private $lumainConfig;

    /**
     * LumainConfig constructor.
     * @param LumainConfigInterface $lumainConfig
     */
    public function __construct(
        LumainConfigInterface $lumainConfig
    )
    {
        $this->lumainConfig = $lumainConfig;
    }

    /**
     * @return mixed
     */
    public function getLocalFile(): string
    {
        return $this->lumainConfig->getLocalFile();
    }

    /**
     * @return bool
     */
    public function getEnvISDown(): bool
    {
        return $this->lumainConfig->getEnvISDown();
    }

    /**
     * @return array
     */
    public function getEnvAllowIPS(): array
    {
        return $this->lumainConfig->getEnvAllowIPS();
    }

    /**
     * @return string
     */
    public function getExcludePath(): string
    {
        return $this->lumainConfig->getExcludePath();
    }

    /**
     * @return int
     */
    public function getResponseStatus(): int
    {
        return $this->lumainConfig->getResponseStatus();
    }

    /**
     * @return string
     */
    public function getResponseMessage(): string
    {
        return $this->lumainConfig->getResponseMessage();
    }
}
