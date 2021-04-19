<?php

namespace Lumaintenance\Service;

/**
 * Interface LumainConfigInterface
 * @package Lumaintenance\Service
 */
interface LumainConfigInterface
{
    /**
     * @return string
     */
    public function getLocalFile(): string;

    /**
     * @return bool
     */
    public function getEnvISDown(): bool;

    /**
     * @return array
     */
    public function getEnvAllowIPS(): array;

    /**
     * @return string
     */
    public function getExcludePath(): string;

    /**
     * @return int
     */
    public function getResponseStatus(): int;

    /**
     * @return string
     */
    public function getResponseMessage(): string;
}
