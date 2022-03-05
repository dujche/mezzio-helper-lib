<?php

declare(strict_types=1);

namespace Dujche\MezzioHelperLib\Handler;

use Dujche\MezzioHelperLib\Entity\EntityInterface;
use Dujche\MezzioHelperLib\Exception\DuplicateRecordException;
use Dujche\MezzioHelperLib\Exception\RuntimeException;
use Dujche\MezzioHelperLib\Service\AddHandlerInterface;
use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class PostHandler implements RequestHandlerInterface
{
    private AddHandlerInterface $addService;

    private LoggerInterface $logger;

    public function __construct(AddHandlerInterface $addService, LoggerInterface $logger)
    {
        $this->addService = $addService;
        $this->logger = $logger;
    }

    /**
     * @throws RuntimeException|Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $post = $request->getParsedBody();

        try {
            $saveResult = $this->performSave($post);
        } catch (DuplicateRecordException $duplicateRecordException) {
            $this->logger->warn($duplicateRecordException->getMessage());
            return new EmptyResponse(409);
        }
        if ($saveResult === null) {
            $this->logger->err('Inserting contact into database failed.');
            throw new RuntimeException();
        }

        return new JsonResponse(
            $saveResult->toArray(),
            201
        );
    }


    /**
     * @param array $post
     * @return EntityInterface|null
     * @throws Exception
     */
    private function performSave(array $post): ?EntityInterface
    {
        $entity = $this->getEntityToSave($post);

        return $this->addService->add($entity) ? $entity : null;
    }

    /**
     * @param array $post
     * @return EntityInterface
     * @throws Exception
     */
    abstract protected function getEntityToSave(array $post): EntityInterface;
}
