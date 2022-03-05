<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLibTest\Handler;

use Dujche\MezzioHelperLib\Entity\EntityInterface;
use Dujche\MezzioHelperLib\Exception\DuplicateRecordException;
use Dujche\MezzioHelperLib\Exception\RuntimeException;
use Dujche\MezzioHelperLib\Handler\PostHandler;
use Dujche\MezzioHelperLib\Service\AddHandlerInterface;
use JsonException;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class PostSellerHandlerTest extends TestCase
{
    private AddHandlerInterface $addService;

    private LoggerInterface $logger;

    private PostHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addService = $this->createMock(AddHandlerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->handler = new class($this->addService, $this->logger) extends PostHandler {

            protected function getEntityToSave(array $post): EntityInterface
            {
                return new class implements EntityInterface{
                    public function toArray(): array
                    {
                        return [];
                    }
                };
            }
        };
    }

    /**
     * @throws RuntimeException
     * @throws JsonException
     */
    public function testResponseOnDuplicateSeller(): void
    {
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects($this->once())->method('getParsedBody')
            ->willReturn([
                'id' => 10,
                'firstName' => 'John',
                'lastName' => 'Doe',
                'country' => 'DE',
                'dateJoined' => '2020-01-01'
            ]);

        $this->addService->expects($this->once())->method('add')
            ->willThrowException(new DuplicateRecordException('foo'));

        $response = $this->handler->handle($requestMock);

        self::assertInstanceOf(EmptyResponse::class, $response);
        self::assertEquals(409, $response->getStatusCode());
    }

    public function testExceptionThrownOnDatabaseProblem(): void
    {
        $this->expectException(RuntimeException::class);

        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects($this->once())->method('getParsedBody')
            ->willReturn([
                'id' => 10,
                'firstName' => 'John',
                'lastName' => 'Doe',
                'country' => 'DE',
                'dateJoined' => '2020-01-01'
            ]);

        $this->logger->expects($this->once())->method('err');

        $this->handler->handle($requestMock);
    }

    /**
     * @throws RuntimeException
     * @throws JsonException
     */
    public function testResponse(): void
    {
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects($this->once())->method('getParsedBody')
            ->willReturn([
                'id' => 10,
                'firstName' => 'John',
                'lastName' => 'Doe',
                'country' => 'DE',
                'dateJoined' => '2020-01-01'
            ]);


        $this->addService->expects($this->once())->method('add')
            ->willReturn(true);

        $response = $this->handler->handle($requestMock);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals(
            '[]',
            $response->getBody()->getContents()
        );
    }
}
