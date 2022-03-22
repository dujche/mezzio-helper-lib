<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLib;

use Dujche\MezzioHelperLib\Error\CustomErrorHandlerMiddleware;
use Dujche\MezzioHelperLib\Log\Factory\LogFactory;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Psr\Log\LoggerInterface;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            ConfigAbstractFactory::class => $this->getConfigAbstractFactories(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                LoggerInterface::class => LogFactory::class,
                CustomErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    private function getConfigAbstractFactories(): array
    {
        return [
            CustomErrorHandlerMiddleware::class => [
                LoggerInterface::class
            ],
        ];
    }
}
