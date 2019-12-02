<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Entity;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EntityEvent extends Event
{
    private $manager;

    private $entity;

    public function __construct(EntityManager $entityManager, object $entity)
    {
        $this->manager = $entityManager;
        $this->entity = $entity;
    }

    public function getManager(): EntityManager
    {
        return $this->manager;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }
}
