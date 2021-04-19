<?php

namespace Lumaintenance\Service;

use Lumaintenance\Entity\IP;

/**
 * Interface LumainServiceInterface
 * @package Lumaintenance\Service
 */
interface LumainServiceInterface
{
    /**
     * @return bool
     */
    public function isDown(): bool;

    /**
     * @return IP
     */
    public function allowIP(): IP;

    /**
     * @return mixed
     */
    public function up();

    /**
     * @param array $allowIPs
     * @return mixed
     */
    public function down(array $allowIPs);
}
