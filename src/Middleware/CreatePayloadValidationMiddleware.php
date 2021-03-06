<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLib\Middleware;

use Dujche\MezzioHelperLib\Exception\ValidationException;
use JsonException;
use Laminas\InputFilter\InputFilter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class CreatePayloadValidationMiddleware implements MiddlewareInterface
{
    private InputFilter $inputFilter;

    private LoggerInterface $logger;

    public function __construct(InputFilter $inputFilter, LoggerInterface $logger)
    {
        $this->inputFilter = $inputFilter;
        $this->logger = $logger;
    }

    /**
     * @throws ValidationException
     * @throws JsonException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->inputFilter->setData($request->getParsedBody());
        $this->runInputFilterCheck();

        return $handler->handle($request);
    }

    /**
     * @throws ValidationException|JsonException
     */
    protected function runInputFilterCheck(): void
    {
        if ($this->inputFilter->isValid()) {
            return;
        }

        $errorMessagesAsArray = $this->inputFilter->getMessages();
        $errorMessageAsArray = current($errorMessagesAsArray);

        $this->logger->error(
            sprintf('Payload Validation failed: %s', json_encode($errorMessagesAsArray, JSON_THROW_ON_ERROR))
        );

        throw new ValidationException(sprintf("Field %s is invalid - %s", key($errorMessagesAsArray), current($errorMessageAsArray)));
    }
}
