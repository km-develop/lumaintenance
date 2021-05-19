<?php

namespace Lumaintenance\Middleware;

use Illuminate\Http\Request;
use Lumaintenance\Service\LumainConfigInterface;
use Lumaintenance\Service\LumainEnvServiceInterface;
use Lumaintenance\Service\LumainLocalServiceInterface;

/**
 * Class MaintenanceMiddleware
 * @package Lumaintenance\Middleware
 */
class MaintenanceMiddleware
{
    /**
     * @var LumainLocalServiceInterface
     */
    private $lumainLocalService;

    /**
     * @var LumainEnvServiceInterface
     */
    private $lumainEnvService;

    /**
     * @var LumainConfigInterface
     */
    private $lumainConfig;

    /**
     * MaintenanceMiddleware constructor.
     * @param LumainLocalServiceInterface $lumainLocalService
     * @param LumainEnvServiceInterface $lumainEnvService
     * @param LumainConfigInterface $lumainConfig
     */
    public function __construct(
        LumainLocalServiceInterface $lumainLocalService,
        LumainEnvServiceInterface $lumainEnvService,
        LumainConfigInterface $lumainConfig
    ) {
        $this->lumainLocalService = $lumainLocalService;
        $this->lumainEnvService = $lumainEnvService;
        $this->lumainConfig = $lumainConfig;
    }

    /**
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $ip = $request->ip();
        $contentType = $request->headers->get('Content-Type');
        $isAPI = $request->ajax() || in_array($contentType, ['application/json', 'text/json'], true);

        $excludePath = $this->trimPath($this->lumainConfig->getExcludePath());
        $requestPath = $this->trimPath($request->path());
        if ($excludePath === $requestPath) {
            return $next($request);
        }

        // Local File
        if ($this->lumainLocalService->isDown()) {
            $allowIPs = $this->lumainLocalService->allowIP()->toArray();
            if (!in_array($ip, $allowIPs, true)) {
                return $this->abortResponse($isAPI);
            }
        }

        // Env
        if ($this->lumainEnvService->isDown()) {
            $allowIPs = $this->lumainEnvService->allowIP()->toArray();
            if (!in_array($ip, $allowIPs, true)) {
                return $this->abortResponse($isAPI);
            }
        }
        return $next($request);
    }

    /**
     * @param bool $isAPI
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    private function abortResponse(bool $isAPI)
    {
        $status = $this->lumainConfig->getResponseStatus();
        $message = $this->lumainConfig->getResponseMessage();

        if ($isAPI) {
            return response(
                [
                    'message' => $message
                ],
                $status
            );
        } else {
            $hasCustomView = view()->exists('lumaintenance.maintenance');
            $viewName = $hasCustomView ? 'lumaintenance.maintenance' : 'lumaintenance::maintenance';
            return response(
                view(
                    $viewName,
                    [
                        'message' => $message
                    ]
                ),
                $status
            );
        }
    }

    /**
     * @param $path
     * @return string|null
     */
    private function trimPath($path): ?string
    {
        return is_null($path) ? null : trim($path, '/');
    }
}
