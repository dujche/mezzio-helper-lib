<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLib\Log\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Log\Logger;
use Laminas\Log\PsrLoggerAdapter;
use Laminas\Log\Writer\Stream;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Log\LoggerInterface;

class LogFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): LoggerInterface
    {
        $logger = new Logger();
        $logger->addWriter(new Stream('php://stdout'));

        return new PsrLoggerAdapter($logger);
    }
}
