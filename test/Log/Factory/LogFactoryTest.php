<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLibTest\Log\Factory;

use Dujche\MezzioHelperLib\Log\Factory\LogFactory;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\Log\PsrLoggerAdapter;
use PHPUnit\Framework\TestCase;

class LogFactoryTest extends TestCase
{
    /**
     * @throws ContainerException
     */
    public function testInvoke(): void
    {
        $containerMock = $this->createMock(ContainerInterface::class);
        $containerMock->expects($this->never())->method('get');
        $factory = new LogFactory();
        $this->assertInstanceOf(PsrLoggerAdapter::class, $factory($containerMock, ''));
    }
}
