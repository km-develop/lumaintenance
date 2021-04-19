<?php

namespace Unit;

use Lumaintenance\Exception\MaintenanceUpException;
use Lumaintenance\Service\LumainConfigInterface;
use Lumaintenance\Service\LumainLocalService;
use Mockery;

/**
 * Class LumainLocalServiceTest
 */
class LumainLocalServiceTest extends \TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $downPath = __DIR__ . '/../storage/framework/down';
        if (is_file($downPath)) {
            unlink($downPath);
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $downPath = __DIR__ . '/../storage/framework/down';
        if (file_exists($downPath)) {
            chmod($downPath, '644');
            unlink($downPath);
        }
    }

    /**
     * @throws \Throwable
     */
    public function testDownStatus()
    {
        $configMock = Mockery::mock(LumainConfigInterface::class);
        $configMock->shouldReceive('getLocalFile')->andReturn('framework/down');
        $service = new LumainLocalService($configMock);
        $this->assertFalse($service->isDown());
        $this->assertFalse(is_file(__DIR__ . '/../storage/framework/down'));
    }

    /**
     * @throws \Throwable
     */
    public function testUpStatus()
    {
        $configMock = Mockery::mock(LumainConfigInterface::class);
        $configMock->shouldReceive('getLocalFile')->andReturn('framework/down');
        $service = new LumainLocalService($configMock);

        $downPath = __DIR__ . '/../storage/framework/down';
        file_put_contents($downPath, \json_encode(
            [
                'allows' => [],
                'updated_at' => ''
            ]
        ));
        $this->assertTrue($service->isDown());
        $this->assertTrue(is_file($downPath));
    }

    /**
     * @throws \Throwable
     */
    public function testDownWithBlankIP()
    {
        $configMock = Mockery::mock(LumainConfigInterface::class);
        $configMock->shouldReceive('getLocalFile')->andReturn('framework/down');
        $configMock->shouldReceive('getEnvAllowIPS')->andReturn([]);
        $service = new LumainLocalService($configMock);

        $this->assertFalse(is_file(__DIR__ . '/../storage/framework/down'));
        $service->down([]);
        $this->assertTrue(is_file(__DIR__ . '/../storage/framework/down'));
        $this->assertEquals($service->allowIP()->toArray(), ['-']);
        $this->assertEquals($service->allowIP()->toString(), '-');
    }

    /**
     * @throws \Throwable
     */
    public function testDownWithLocalFileAllowIP()
    {
        $configMock = Mockery::mock(LumainConfigInterface::class);
        $configMock->shouldReceive('getLocalFile')->andReturn('framework/down');
        $configMock->shouldReceive('getEnvAllowIPS')->andReturn([]);
        $service = new LumainLocalService($configMock);

        $this->assertFalse(is_file(__DIR__ . '/../storage/framework/down'));
        $service->down(['192.168.0.1', '10.0.0.1']);
        $this->assertTrue(is_file(__DIR__ . '/../storage/framework/down'));
        $this->assertEquals($service->allowIP()->toArray(), ['192.168.0.1', '10.0.0.1']);
        $this->assertEquals($service->allowIP()->toString(), '192.168.0.1,10.0.0.1');
    }

    /**
     * @throws \Throwable
     */
    public function testDownWithEnvAllowIP()
    {
        $configMock = Mockery::mock(LumainConfigInterface::class);
        $configMock->shouldReceive('getLocalFile')->andReturn('framework/down');
        $configMock->shouldReceive('getEnvAllowIPS')->andReturn(['192.168.0.1', '10.0.0.1']);
        $service = new LumainLocalService($configMock);

        $this->assertFalse(is_file(__DIR__ . '/../storage/framework/down'));
        $service->down([]);
        $this->assertTrue(is_file(__DIR__ . '/../storage/framework/down'));
        $this->assertEquals($service->allowIP()->toArray(), ['192.168.0.1', '10.0.0.1']);
        $this->assertEquals($service->allowIP()->toString(), '192.168.0.1,10.0.0.1');
    }

    /**
     * @throws \Throwable
     */
    public function testUpFilePermission()
    {
        $configMock = Mockery::mock(LumainConfigInterface::class);
        $configMock->shouldReceive('getLocalFile')->andReturn('framework/down');
        $configMock->shouldReceive('getEnvAllowIPS')->andReturn([]);
        $service = new LumainLocalService($configMock);

        $this->expectException(MaintenanceUpException::class);
        file_put_contents(__DIR__ . '/../storage/framework/down', '');
        chmod(__DIR__ . '/../storage/framework/down', '000');
        $this->assertTrue($service->isDown());
        $service->up();
    }
}
