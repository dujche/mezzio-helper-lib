<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLibTest\Middleware;

use Dujche\MezzioHelperLib\Exception\ValidationException;
use Dujche\MezzioHelperLib\Middleware\CreatePayloadValidationMiddleware;
use JsonException;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\InputFilter\InputFilter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class CreatePayloadValidationMiddlewareTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testInvalidInput(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('baz');

        $requestMock = $this->createMock(ServerRequestInterface::class);

        $handlerMock = $this->createMock(RequestHandlerInterface::class);
        $handlerMock->expects($this->never())->method('handle');

        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->once())->method('error');

        $inputFilterMock = $this->createMock(InputFilter::class);
        $inputFilterMock->expects($this->once())->method('isValid')->willReturn(false);
        $inputFilterMock->expects($this->once())->method('getMessages')->willReturn(
            [
                'foo' => [
                    'bar' => 'baz'
                ]
            ]
        );

        $middleware = new CreatePayloadValidationMiddleware($inputFilterMock, $loggerMock);
        $middleware->process($requestMock, $handlerMock);
    }

    /**
     * @throws ValidationException
     * @throws JsonException
     */
    public function testValidInput(): void
    {
        $requestMock = $this->createMock(ServerRequestInterface::class);

        $handlerMock = $this->createMock(RequestHandlerInterface::class);
        $handlerMock->expects($this->once())->method('handle')
            ->with($requestMock)->willReturn(new EmptyResponse(200));

        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->never())->method('error');

        $inputFilterMock = $this->createMock(InputFilter::class);
        $inputFilterMock->expects($this->once())->method('isValid')->willReturn(true);


        $middleware = new CreatePayloadValidationMiddleware($inputFilterMock, $loggerMock);
        $response = $middleware->process($requestMock, $handlerMock);

        $this->assertInstanceOf(EmptyResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }
}
