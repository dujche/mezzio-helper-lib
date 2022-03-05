<?php

namespace Dujche\MezzioHelperLib\Service;

use Dujche\MezzioHelperLib\Entity\EntityInterface;

interface AddHandlerInterface
{
    public function add(EntityInterface $entity): bool;
}