<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLib\Error;

use Dujche\MezzioHelperLib\Exception\RuntimeException;
use Dujche\MezzioHelperLib\Exception\ValidationException;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CustomErrorHandlerMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $validationException) {
            $this->logger->err('Caught ValidationException: ' . $validationException->getMessage());
            return new JsonResponse(['error' => $validationException->getMessage()], 400);
        } catch (RuntimeException $runtimeException) {
            $this->logger->err('Caught RuntimeException: ' . $runtimeException->getMessage());
            return new EmptyResponse(500);
        } catch (\Throwable $exception) {
            $this->logger->err('Caught '. get_class($exception) . ' exception: ' . $exception->getMessage());
            return new EmptyResponse(500);
        }
    }
}
