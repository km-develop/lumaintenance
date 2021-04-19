<?php

namespace Lumaintenance\Entity;

/**
 * Class IP
 * @package Lumaintenance\Entity
 */
class IP
{
    /**
     * @var array
     */
    private $ips;

    /**
     * IP constructor.
     * @param array $ips
     */
    public function __construct($ips = [])
    {
        $this->ips = $ips;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return implode(',', $this->ips);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->ips;
    }
}
