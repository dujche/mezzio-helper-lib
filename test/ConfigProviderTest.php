<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLibTest;

use Dujche\MezzioHelperLib\ConfigProvider;
use Dujche\MezzioHelperLib\Log\Factory\LogFactory;
use Laminas\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

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
                    ],
                ]
            ],
            $configProvider()
        );
    }
}
