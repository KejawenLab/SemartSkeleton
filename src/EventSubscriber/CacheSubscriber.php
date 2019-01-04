<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use KejawenLab\Semart\Skeleton\AppEvent;
use KejawenLab\Semart\Skeleton\Contract\Repository\CacheableRepositoryInterface;
use KejawenLab\Semart\Skeleton\Entity\FilterEntity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CacheSubscriber implements EventSubscriberInterface
{
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function filterEntity(FilterEntity $filterEntity)
    {
        $entity = $filterEntity->getEntity();
        $repository = $filterEntity->getManager()->getRepository(get_class($entity));

        if ($repository instanceof CacheableRepositoryInterface && Request::METHOD_GET === $this->request->getMethod()) {
            $repository->setCacheable(true);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            AppEvent::PRE_COMMIT_EVENT => [['filterEntity']],
        ];
    }
}
