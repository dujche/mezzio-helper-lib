<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLibTest;

use Dujche\MezzioHelperLib\ConfigProvider;
use Dujche\MezzioHelperLib\Error\CustomErrorHandlerMiddleware;
use Dujche\MezzioHelperLib\Log\Factory\LogFactory;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ConfigProviderTest extends TestCase
{
    public function testInvoke(): void
    {
        $configProvider = new ConfigProvider();
        $this->assertEquals(
            [
                'dependencies' => [
                    'factories' => [
                        LoggerInterface::class => LogFactory::class,
                        CustomErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
                    ],
                ],
                ConfigAbstractFactory::class => [
                    CustomErrorHandlerMiddleware::class => [
                        LoggerInterface::class
                    ]
                ]
            ],
            $configProvider()
        );
    }
}
