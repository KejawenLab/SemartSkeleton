<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Cache\CacheHandler;
use KejawenLab\Semart\Skeleton\Entity\PersistEntityEvent;
use KejawenLab\Semart\Skeleton\Entity\RemoveEntityEvent;
use PHLAK\Twine\Str;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class AdminController extends AbstractController
{
    private $eventDispatcher;

    private $cacheProvider;

    private $title = '';

    public function __construct(EventDispatcherInterface $eventDispatcher, CacheHandler $cacheProvider)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->cacheProvider = $cacheProvider;
    }

    public function setPageTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $key
     * @param mixed  $content
     *
     * @return mixed
     */
    protected function cache(string $key, $content = null)
    {
        if (!$content) {
            $this->cacheProvider->cache($key, $content);
        }

        return $this->cacheProvider->getItem($key);
    }

    protected function isCached(string $key): bool
    {
        return $this->cacheProvider->isCached($key);
    }

    protected function commit(object $entity): void
    {
        $manager = $this->getDoctrine()->getManager();

        $this->eventDispatcher->dispatch(new PersistEntityEvent($manager, $entity));

        $manager->persist($entity);
        $manager->flush();
    }

    protected function remove(object $entity): void
    {
        $manager = $this->getDoctrine()->getManager();

        $this->eventDispatcher->dispatch(new RemoveEntityEvent($manager, $entity));

        $manager->remove($entity);
        $manager->flush();
    }

    protected function getPageTitle(): string
    {
        return Str::make($this->title)->lowercase()->uppercaseWords()->__toString();
    }
}
