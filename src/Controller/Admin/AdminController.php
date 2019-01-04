<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\AppEvent;
use KejawenLab\Semart\Skeleton\Entity\FilterEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class AdminController extends AbstractController
{
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function commit(object $entity)
    {
        $manager = $this->getDoctrine()->getManager();

        $this->eventDispatcher->dispatch(AppEvent::PRE_COMMIT_EVENT, new FilterEntity($manager, $entity));

        $manager->persist($entity);
        $manager->flush();
    }

    protected function remove(object $entity)
    {
        $manager = $this->getDoctrine()->getManager();

        $this->eventDispatcher->dispatch(AppEvent::PRE_COMMIT_EVENT, new FilterEntity($manager, $entity));

        $manager->remove($entity);
        $manager->flush();
    }
}
