<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLib\Error;

use Dujche\MezzioHelperLib\Exception\RuntimeException;
use Dujche\MezzioHelperLib\Exception\ValidationException;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

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
            $this->logger->error(
                sprintf("Caught ValidationException: %s", $validationException->getMessage())
            );
            return new JsonResponse(['error' => $validationException->getMessage()], 400);
        } catch (RuntimeException $runtimeException) {
            $this->logger->error(
                sprintf("Caught RuntimeException: %s", $runtimeException->getMessage())
            );
            return new EmptyResponse(500);
        } catch (\Throwable $exception) {
            $this->logger->error(
                sprintf("Caught %s exception in %s at %s: %s",
                    get_class($exception),
                    $exception->getFile(),
                    $exception->getLine(),
                    $exception->getMessage()
                )
            );
            return new EmptyResponse(500);
        }
    }
}
