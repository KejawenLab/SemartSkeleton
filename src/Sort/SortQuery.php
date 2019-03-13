<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Sort;

use Doctrine\Common\Annotations\Reader;
use KejawenLab\Semart\Skeleton\AppEvent;
use KejawenLab\Semart\Skeleton\Pagination\FilterPagination;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SortQuery implements EventSubscriberInterface
{
    private $annotationReader;

    public function __construct(Reader $reader)
    {
        $this->annotationReader = $reader;
    }

    public function apply(FilterPagination $event): void
    {
        $request = $event->getRequest();
        if ('' === $sortField = $request->query->get('s', '')) {
            return;
        }

        $queryBuilder = $event->getQueryBuilder();
        $annotations = $this->annotationReader->getClassAnnotations(new \ReflectionClass($event->getEntityClass()));
        foreach ($annotations as $annotation) {
            if (!$annotation instanceof Sortable) {
                continue;
            }

            $sortableFields = $annotation->getFields();
            if (!in_array($sortField, $sortableFields)) {
                continue;
            }

            $queryBuilder->addOrderBy(sprintf('o.%s', $sortField), $request->query->get('d', 'ASC'));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AppEvent::PAGINATION_EVENT => [['apply', -255]],
        ];
    }
}