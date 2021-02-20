<?php

namespace App\Form\DataTransformer;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToIdTransformer implements DataTransformerInterface
{
    protected ObjectManager $objectManager;

    public function __construct(string $class, ObjectManager $objectManager)
    {
        $this->class = $class;
        $this->objectManager = $objectManager;
    }

    public function transform($entity)
    {
        if ($entity === null) {
            return null;
        }

        if (is_int($entity)) {
            return $entity;
        }

        return $entity->getId();
    }

    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $entity = $this->objectManager->getRepository($this->class)->find($id);

        if ($entity === null) {
            throw new TransformationFailedException('Entity not found');
        }

        return $entity;
    }
}
