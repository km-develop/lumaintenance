<?php

namespace Lumaintenance\Service;

/**
 * Class LumainConfig
 * @package Lumaintenance\Service
 */
class LumainConfig implements LumainConfigInterface
{
    /**
     * @var
     */
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getLocalFile(): string
    {
        return $this->config['maintenance']['local']['file'];
    }

    /**
     * @return bool
     */
    public function getEnvISDown(): bool
    {
        return !empty($this->config['maintenance']['env']['is_down']);
    }

    /**
     * @return array
     */
    public function getEnvAllowIPS(): array
    {
        $ip = $this->config['maintenance']['env']['allow_ips'];
        return empty($ip) ? ['-'] : explode(',', $ip);
    }

    /**
     * @return string
     */
    public function getExcludePath(): string
    {
        return $this->config['maintenance']['env']['exclude_path'];
    }

    /**
     * @return int
     */
    public function getResponseStatus(): int
    {
        return $this->config['response']['status'];
    }

    /**
     * @return string
     */
    public function getResponseMessage(): string
    {
        return $this->config['response']['message'];
    }
}
