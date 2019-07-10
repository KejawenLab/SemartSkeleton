<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Entity\EntityEvent;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class AdminController extends AbstractController
{
    private $eventDispatcher;

    private $cacheProvider;

    private $item;

    public function __construct(EventDispatcherInterface $eventDispatcher, AdapterInterface $cacheProvider)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->cacheProvider = $cacheProvider;
    }

    /**
     * @param string $key
     * @param null   $content
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    protected function cache(string $key, $content = null)
    {
        if ($this->item) {
            return $this->item;
        }

        $item = $this->cacheProvider->getItem($key);
        if (!$item->isHit()) {
            $item->set($content);
            $item->expiresAfter((new \DateInterval('PT17S')));

            $this->cacheProvider->save($item);
        }

        return $item->get();
    }

    protected function isCached(string $key): bool
    {
        $item = $this->cacheProvider->getItem($key);
        if ($this->item = $item->get()) {
            return true;
        }

        return false;
    }

    protected function commit(object $entity): void
    {
        $manager = $this->getDoctrine()->getManager();

        $this->eventDispatcher->dispatch(Application::PRE_COMMIT_EVENT, new EntityEvent($manager, $entity));

        $manager->persist($entity);
        $manager->flush();
    }

    protected function remove(object $entity): void
    {
        $manager = $this->getDoctrine()->getManager();

        $this->eventDispatcher->dispatch(Application::PRE_COMMIT_EVENT, new EntityEvent($manager, $entity));

        $manager->remove($entity);
        $manager->flush();
    }
}
