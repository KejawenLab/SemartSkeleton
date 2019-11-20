<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Semart\Skeleton\Entity\Menu;
use KejawenLab\Semart\Skeleton\Pagination\PaginationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class MenuSubscriber implements EventSubscriberInterface
{
    public function filterPagination(PaginationEvent $event)
    {
        if (Menu::class !== $event->getEntityClass()) {
            return;
        }

        $request = $event->getRequest();
        if (!$request) {
            return;
        }

        $filter = $request->query->get('f');
        if ($filter) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $event->getQueryBuilder();
            $queryBuilder->join(sprintf('%s.parent', $event->getJoinAlias('root')), 'p');
            $queryBuilder->andWhere($queryBuilder->expr()->eq('p.id', $queryBuilder->expr()->literal($filter)));
            $event->addJoinAlias('parent', 'p');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PaginationEvent::class => 'filterPagination',
        ];
    }
}
