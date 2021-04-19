<?php

namespace Unit;

use Lumaintenance\Exception\UnImplementedException;
use Lumaintenance\Service\LumainConfigInterface;
use Lumaintenance\Service\LumainEnvService;
use Mockery;
use TestCase;

/**
 * Class LumainEnvServiceTest
 */
class LumainEnvServiceTest extends TestCase
{
    public function testDownStatus()
    {
        // Blank Allow IP Environment
        $configMock = Mockery::mock(LumainConfigInterface::class);
        $configMock->shouldReceive('getEnvISDown')->andReturn(true);
        $configMock->shouldReceive('getEnvAllowIPS')->andReturn([]);
        $service = new LumainEnvService($configMock);
        $this->assertTrue($service->isDown());
        $this->assertEmpty($service->allowIP()->toArray());
        $this->assertEmpty($service->allowIP()->toString());

        // Add Allow IP Environment
        $configMock = Mockery::mock(LumainConfigInterface::class);
        $configMock->shouldReceive('getEnvISDown')->andReturn(true);
        $configMock->shouldReceive('getEnvAllowIPS')->andReturn(['192.168.0.1', '10.0.0.1']);
        $service = new LumainEnvService($configMock);
        $this->assertTrue($service->isDown());
        $this->assertEquals(count($service->allowIP()->toArray()), 2);
        $this->assertEquals($service->allowIP()->toString(), '192.168.0.1,10.0.0.1');
    }

    /**
     *
     */
    public function testUpStatus()
    {
        $configMock = Mockery::mock(LumainConfigInterface::class);
        $configMock->shouldReceive('getEnvISDown')->andReturn(false);
        $configMock->shouldReceive('getEnvAllowIPS')->andReturn([]);

        $service = new LumainEnvService($configMock);
        $this->assertFalse($service->isDown());
        $this->assertEmpty($service->allowIP()->toArray());
        $this->assertEmpty($service->allowIP()->toString());
    }

    /**
     * @throws UnImplementedException
     */
    public function testUpFailed()
    {
        $configMock = Mockery::mock(LumainConfigInterface::class);
        $service = new LumainEnvService($configMock);

        $this->expectException(UnImplementedException::class);
        $service->up();
    }

    /**
     * @throws UnImplementedException
     */
    public function testDownFailed()
    {
        $configMock = Mockery::mock(LumainConfigInterface::class);
        $service = new LumainEnvService($configMock);

        $this->expectException(UnImplementedException::class);
        $service->down([]);
    }
}
