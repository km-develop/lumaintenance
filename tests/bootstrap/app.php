<?php
// for Tests

require_once __DIR__ . '/../../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

return new Laravel\Lumen\Application(
    dirname(__DIR__)
);
